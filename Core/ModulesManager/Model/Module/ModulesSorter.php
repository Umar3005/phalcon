<?php declare(strict_types=1);

namespace Core\ModulesManager\Model\Module;

use Core\ModulesManager\Api\Data\DataMapper;

class ModulesSorter implements DataMapper
{
    public function sortBySequence(array $origList): array
    {
        ksort($origList);

        $expanded = [];
        foreach (array_keys($origList) as $moduleName) {
            $sequence = $this->expandSequence($origList, $moduleName);
            asort($sequence);

            $expanded[] = [
                self::NAME_FIELD => $moduleName,
                self::SEQUENCE_SET_FIELD => array_flip($sequence),
            ];
        }

        // Use "bubble sorting" because usort does not check each pair of elements and in this case it is important
        $total = count($expanded);
        for ($i = 0; $i < $total - 1; $i++) {
            for ($j = $i; $j < $total; $j++) {
                if (isset($expanded[$i][self::SEQUENCE_SET_FIELD][$expanded[$j][self::NAME_FIELD]])) {
                    $temp = $expanded[$i];
                    $expanded[$i] = $expanded[$j];
                    $expanded[$j] = $temp;
                }
            }
        }

        $result = [];
        foreach ($expanded as $pair) {
            $result[$pair[self::NAME_FIELD]] = $origList[$pair[self::NAME_FIELD]];
        }

        return $result;
    }

    private function expandSequence(array $list, string $name, $accumulated = []): array
    {
        $accumulated[$name] = true;
        $result = $list[$name][self::SEQUENCE_FIELD];
        $allResults = [];
        foreach ($result as $relatedName) {
            if (isset($accumulated[$relatedName])) {
                throw new \LogicException("Circular sequence reference from '{$name}' to '{$relatedName}'.");
            }
            if (!isset($list[$relatedName])) {
                continue;
            }
            $allResults[] = $this->expandSequence($list, $relatedName, $accumulated);
        }
        $allResults[] = $result;
        return array_unique(array_merge([], ...$allResults));
    }
}
