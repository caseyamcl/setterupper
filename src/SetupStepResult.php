<?php
declare(strict_types=1);

namespace SetterUpper;

use InvalidArgumentException;
use Throwable;

/**
 * Class SetupStepResult
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupStepResult
{
    public const STATUS_SUCCEEDED = 'succeeded';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';
    public const STATUS_UNDETERMINED = '';

    /**
     * @var string
     */
    private $status = self::STATUS_UNDETERMINED;

    /**
     * @var string
     */
    private $message;

    /**
     * @var bool
     */
    private $required;

    /**
     * @var array
     */
    private $notes;

    /**
     * @param string $message
     * @param bool $required
     * @param array $notes
     *
     * @return static
     */
    public static function succeed(string $message, bool $required = true, array $notes = [])
    {
        return new static(self::STATUS_SUCCEEDED, $message, $required, $notes);
    }

    /**
     * @param string $message
     * @param bool $required
     * @param array $notes
     *
     * @return static
     */
    public static function fail(string $message, bool $required = true, array $notes = [])
    {
        return new static(self::STATUS_FAILED, $message, $required, $notes);
    }

    /**
     * @param string $message
     * @param bool $required
     * @param array $notes
     *
     * @return static
     */
    public static function skip(string $message, bool $required = true, array $notes = [])
    {
        return new static(self::STATUS_SKIPPED, $message, $required, $notes);
    }

    /**
     * @param string $message
     * @param string[] ...$notes
     * @return static
     */
    public static function notesOnly(string $message, string ...$notes)
    {
        $notes = ! is_array($notes) ? [$notes] : $notes;
        return new static(self::STATUS_UNDETERMINED, $message, false, $notes);
    }

    /**
     * SetupStepResult constructor.
     * @param string $status
     * @param string $message
     * @param bool $required
     * @param array|string[] $notes
     */
    public function __construct(
        ?string $status = null,
        string $message = '',
        bool $required = true,
        array $notes = []
    ) {
        if (! in_array($status, self::getAllowedStatuses(), true)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid status: %s (allowed: %s)',
                $status,
                implode(', ', array_merge(['[undetermined]'], array_filter(self::getAllowedStatuses())))
            ));
        }

        $this->status = $status;
        $this->message = $message;
        $this->required = $required;
        $this->notes = $notes;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function wasRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return array
     */
    public function getNotes(): array
    {
        return $this->notes;
    }

    /**
     * Get a copy of this result object with added notes.
     *
     * @param string ...$notes
     * @return SetupStepResult
     */
    public function withAddedNotes(string ...$notes): SetupStepResult
    {
        $that = clone $this;
        $that->notes = array_merge($that->notes, $notes);
        return $that;
    }

    /**
     * Get allowed statuses
     *
     * @return array|string[]
     */
    protected static function getAllowedStatuses(): array
    {
        return ['', self::STATUS_SKIPPED, self::STATUS_FAILED, self::STATUS_SUCCEEDED];
    }
}
