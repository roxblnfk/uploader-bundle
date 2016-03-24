<?php
/**
 * Copyright © 2016 Elbek Azimov. Contacts: <atom.azimov@gmail.com>.
 */

namespace Atom\UploaderBundle\Mapping;

use Atom\UploaderBundle\DependencyInjection\Configuration;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
abstract class AbstractMappingHelper implements IMappingHelper
{
    /**
     * @param array $mappings
     * @param array|string $options
     *
     * @return bool
     */
    public function hasOptionInMappings(array $mappings, $options)
    {
        foreach ($mappings as $mapping) {
            foreach ((array)$options as $option) {
                if ($mapping[$option]) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array $mappings
     * @param $className
     *
     * @return array|bool
     */
    public function findMappingByClassName(array $mappings, $className)
    {
        $highPriorityMappings = [];
        $lowPriorityMappings = [];

        foreach ($mappings as $mappedClass => $mapping) {
            if ($mappedClass === $className) {
                array_unshift($highPriorityMappings, $mapping);
                continue;
            }

            if (is_a($mappedClass, $className, true)) {
                $highPriorityMappings[] = $mapping;
                continue;
            }

            if (in_array($mappedClass, class_implements($className))) {
                array_unshift($lowPriorityMappings, $mapping);
                continue;
            }

            if (in_array($mappedClass, class_uses($className))) {
                $lowPriorityMappings[] = $mapping;
                continue;
            }
        }

        return $this->mergeMapping($highPriorityMappings, $lowPriorityMappings);
    }

    private function mergeMapping(array $highPriorityMappings, array $lowPriorityMappings)
    {
        $mappings = array_reverse(array_merge($highPriorityMappings, $lowPriorityMappings));

        if (empty($mappings)) {
            return false;
        }

        $defaults = Configuration::getMappingDefaults();
        $result = $defaults;

        foreach ($mappings as $mapping) {
            foreach ($mapping as $key => $value) {
                if ($defaults[$key] === $value) {
                    continue;
                }

                $result[$key] = $value;
            }
        }

        return $result;
    }
}
