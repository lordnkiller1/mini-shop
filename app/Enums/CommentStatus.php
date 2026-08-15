<?php


namespace App\Enums;

enum CommentStatus: int
{
    case Pending = 0;
    case Approved = 1;
    case Rejected = 2;
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'در انتظار',
            self::Approved => 'تایید شده',
            self::Rejected => 'رد شده',
        };
    }
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $status) {
            $options[$status->value] = $status->label();
        }

        return $options;
    }
}
