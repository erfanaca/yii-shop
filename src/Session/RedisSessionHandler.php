<?php

declare(strict_types=1);

namespace App\Session;

use Redis;
use SessionHandlerInterface;

final class RedisSessionHandler implements SessionHandlerInterface
{
    private Redis $redis;

    public function __construct(
        private readonly string $prefix = 'shop:session:',
        private readonly int $ttl = 3600,
    ) {
        $this->redis = new Redis();

        $this->redis->connect('127.0.0.1', 6379);
        $this->redis->select(1);
    }

    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        $this->redis->close();

        return true;
    }

    public function read(string $id): string|false
    {
        $data = $this->redis->get($this->prefix . $id);

        return $data === false ? '' : $data;
    }

    public function write(string $id, string $data): bool
    {
        return $this->redis->setex(
            $this->prefix . $id,
            $this->ttl,
            $data,
        );
    }

    public function destroy(string $id): bool
    {
        $this->redis->del($this->prefix . $id);

        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        return 0;
    }
}
