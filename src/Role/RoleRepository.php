<?php declare(strict_types=1);
namespace App\Role;
use App\Permission\Permission;
use DateTimeImmutable;
use Yiisoft\Db\Connection\ConnectionInterface;
final class RoleRepository {
 public function __construct(private readonly ConnectionInterface $db) {}
 public function findAll(): array {return array_map(fn($r)=>new Role((int)$r['id'],(string)$r['title']),$this->db->createQuery()->from('roles')->all());}
 public function findById(int $id): ?Role {$r=$this->db->createQuery()->from('roles')->where(['id'=>$id])->one(); return $r?new Role((int)$r['id'],(string)$r['title']):null;}
 public function create(string $title): void {$this->db->createCommand()->insert('roles',['title'=>$title])->execute();}
 public function update(Role $role,string $title): void {$this->db->createCommand()->update('roles',['title'=>$title],['id'=>$role->getId()])->execute();}
 public function delete(Role $role): void {$this->db->createCommand()->delete('roles',['id'=>$role->getId()])->execute();}
 public function permissionIds(int $roleId): array {return array_map(fn($r)=>(int)$r['permission_id'],$this->db->createQuery()->select('permission_id')->from('role_permissions')->where(['role_id'=>$roleId])->all());}
 public function syncPermissions(int $roleId,array $ids): void {$this->db->createCommand()->delete('role_permissions',['role_id'=>$roleId])->execute(); foreach($ids as $id){$this->db->createCommand()->insert('role_permissions',['role_id'=>$roleId,'permission_id'=>$id])->execute();}}
}
