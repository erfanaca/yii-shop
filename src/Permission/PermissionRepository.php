<?php declare(strict_types=1);
namespace App\Permission;
use Yiisoft\Db\Connection\ConnectionInterface;
final class PermissionRepository {
 public function __construct(private readonly ConnectionInterface $db) {}
 public function findAll(): array {return array_map(fn($r)=>new Permission((int)$r['id'],(string)$r['title']),$this->db->createQuery()->from('permissions')->all());}
 public function findById(int $id): ?Permission {$r=$this->db->createQuery()->from('permissions')->where(['id'=>$id])->one(); return $r?new Permission((int)$r['id'],(string)$r['title']):null;}
 public function create(string $title): void {$this->db->createCommand()->insert('permissions',['title'=>$title])->execute();}
 public function update(Permission $p,string $title): void {$this->db->createCommand()->update('permissions',['title'=>$title],['id'=>$p->getId()])->execute();}
 public function delete(Permission $p): void {$this->db->createCommand()->delete('permissions',['id'=>$p->getId()])->execute();}
}
