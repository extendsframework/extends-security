<?php
declare(strict_types=1);

namespace ExtendsFramework\Security\Authorization\Permission;

use ExtendsFramework\Security\Authorization\Permission\Exception\InvalidPermissionNotation;

class Permission implements PermissionInterface
{
    /**
     * Character to match everything in a section of the notation.
     *
     * @var string
     */
    protected $wildcard = '*';

    /**
     * Character to divide notation sections.
     *
     * @var string
     */
    protected $divider = ':';

    /**
     * Character to divide parts in a section.
     *
     * @var string
     */
    protected $separator = ',';

    /**
     * Permission notation.
     *
     * @var string
     */
    protected $notation;

    /**
     * Case sensitive regular expression to verify notation.
     *
     * @var string
     */
    protected $pattern = '/^(\*|\w+(,\w+)*)(:(\*|\w+(,\w+)*))*$/';

    /**
     * Permission constructor.
     *
     * @param string $notation
     * @throws InvalidPermissionNotation
     */
    public function __construct(string $notation)
    {
        if ((bool)preg_match($this->pattern, $notation) === false) {
            throw new InvalidPermissionNotation($notation);
        }

        $this->notation = $notation;
    }

    /**
     * @inheritDoc
     */
    public function implies(PermissionInterface $permission): bool
    {
        if (!$permission instanceof static) {
            return false;
        }

        $left = $this->getSections();
        $right = $permission->getSections();

        foreach ($right as $index => $section) {
            if (array_key_exists($index, $left) === false) {
                return true;
            }

            if (array_intersect($section, $left[$index]) === [] && in_array($this->wildcard, $left[$index], true) === false) {
                return false;
            }
        }

        foreach (array_slice($left, count($right)) as $section) {
            if (in_array($this->wildcard, $section, true) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get exploded notation string.
     *
     * @return array
     */
    protected function getSections(): array
    {
        $sections = explode($this->divider, $this->notation);
        foreach ($sections as $index => $section) {
            $sections[$index] = explode($this->separator, $section);

        }

        return $sections;
    }
}
