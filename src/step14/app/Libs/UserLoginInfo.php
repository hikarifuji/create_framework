<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * ユーザのログイン情報を保持するクラス
 * @package App\Libs
 */
class UserLoginInfo
{
    /**
     * @var int ユーザID。usersテーブルのidカラム値
     */
    private int $id;

    /**
     * @var string ハンドルネーム
     */
    private string $name;

    /**
     * @var string メールアドレス
     */
    private string $mail;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }
}