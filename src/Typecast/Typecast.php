<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Cycle\Typecast;

use Spiral\Cycle\Exception\TypecastException;
use Spiral\Database\DatabaseInterface;

final class Typecast implements TypecastInterface
{
    /** @var array */
    private $rules;

    /**
     * @inheritdoc
     */
    public function cast(array $values, DatabaseInterface $db): array
    {
        try {
            foreach ($this->rules as $key => $rule) {
                if (!array_key_exists($key, $values)) {
                    continue;
                }

                if (method_exists($this, $rule)) {
                    $rule = [self::class, $rule];
                }

                $values[$key] = call_user_func($rule, $values[$key], $db);
            }
        } catch (\Throwable $e) {
            throw new TypecastException("Unable to typecast `$key`", $e->getCode(), $e);
        }

        return $values;
    }

    /**
     * @inheritdoc
     */
    public function withRules(array $rules): TypecastInterface
    {
        $tc = clone $this;
        $tc->rules = $rules;

        return $tc;
    }

    /**
     * @param mixed $value
     * @return int
     */
    public static function int($value): int
    {
        return intval($value);
    }

    /**
     * @param mixed $value
     * @return float
     */
    public static function float($value): float
    {
        return floatval($value);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function bool($value): bool
    {
        return boolval($value);
    }

    /**
     * Typecast value into datetime.
     *
     * @param string|int        $value
     * @param DatabaseInterface $db
     * @return \DateTimeInterface
     *
     * @throws \Exception
     */
    public static function datetime($value, DatabaseInterface $db): \DateTimeInterface
    {
        if (!is_scalar($value)) {
            return null;
        }

        return new \DateTimeImmutable($value, $db->getDriver()->getTimezone());
    }

    /**
     * Perform strict value comparision.
     *
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function compare($a, $b)
    {
        if ($a == $b) {
            return 0;
        }

        return ($a > $b) ? 1 : -1;
    }
}