<?php

namespace MacFJA\Math;

/**
 * Utils class CombinationBuilder.
 *
 * Some functions around combination and factorial
 *
 * @package MacFJA\Math
 */
class CombinationBuilder
{
    /** @var array A cache of previous calculated factorial */
    public static $factorialCache = array(
        // Some low value factorial
        0  => 1,
        1  => 1,
        2  => 2,
        3  => 6,
        4  => 24,
        5  => 120,
        6  => 720,
        7  => 5040,
        8  => 40320,
        9  => 362880,
        10 => 3628800
    );

    /**
     * Return the factorial (n!) of the parameter $wanted.
     *
     * If GMP is installed, GMP will be used to calculate the factorial,
     * If libbcmath is installed, bcmath will be used for all mathematics operation.
     *
     * If none of previous library is available, then basic php functions will be used,
     * so the function could result in an error or php can return INF.
     *
     * If GMP is not installed, be aware of the "Maximum function nesting level" value.
     *
     * @param int|string $wanted The wanted factorial base
     *
     * @return int|string
     *
     * @throws \InvalidArgumentException if [$wanted] is not a positive number
     */
    public static function factorial($wanted) {
        // Check input data
        if (!is_numeric($wanted) || $wanted < 0) {
            throw new \InvalidArgumentException('The [wanted] parameter MUST be a positive number');
        }

        // Use GMP extension if available
        if (function_exists('gmp_fact')) {
            return gmp_strval(gmp_fact($wanted));
        }

        // Use cache if exist (for performance)
        if (array_key_exists($wanted, self::$factorialCache)) {
            return self::$factorialCache[$wanted];
        }

        if ($wanted === 1 || $wanted === 0) {
            $value = 1;
        } else {
            if (function_exists('gmp_mul')) {
                $value = gmp_strval(gmp_mul($wanted, self::factorial($wanted - 1)));
            } elseif (function_exists('bcmul')) {
                $value = bcmul($wanted, self::factorial($wanted - 1));
            } else {
                $value = $wanted * self::factorial($wanted - 1);
            }
        }
        // Save in cache for later use
        self::$factorialCache[$wanted] = $value;

        return $value;
    }

    /**
     * Empty the factorial cache (reduce memory usage, but slow down factorial calculation)
     */
    public static function emptyFactorialCache() {
        self::$factorialCache = array();
    }

    /**
     * Return the number of not ordered, none repetitive combination of size [$combinationSize] existing in [$itemCount] elements
     *
     * If GMP is installed, GMP will be used to compute this value,
     * If libbcmath is installed, bcmath will be used for all mathematics operation.
     *
     * If none of previous library is available, then basic php functions will be used,
     * so the function could result in an error or php can return INF.
     *
     * If GMP is not installed, be aware of the "Maximum function nesting level" value.
     *
     * @param int $itemCount       The number of possible element
     * @param int $combinationSize The size of each combination
     *
     * @return int The number of combination
     *
     * @throws \InvalidArgumentException If [$itemCount] < 0 or [$combinationSize] > [$itemCount]
     */
    public static function getCombinationCount($itemCount, $combinationSize) {
        if (function_exists('gmp_mul') && function_exists('gmp_div')) {
            return gmp_strval(
                gmp_div(self::factorial($itemCount),
                    gmp_mul(self::factorial($combinationSize), self::factorial($itemCount - $combinationSize))
                )
            );
        } elseif (function_exists('bcmul') && function_exists('bcdiv')) {
            return bcdiv(self::factorial($itemCount),
                bcmul(self::factorial($combinationSize), self::factorial($itemCount - $combinationSize))
            );
        }
        return self::factorial($itemCount) / (self::factorial($combinationSize) * self::factorial($itemCount - $combinationSize));
    }

    /**
     * Return all not ordered, none repetitive combination of 2 (each combination contains 2 elements)
     * existing in a set of [$n] elements
     *
     * @param int $n
     *
     * @return array[]
     */
    protected static function combinationOfTwo($n) {
        // Build all possible values
        $allValues = array();
        for($i=1;$i<=$n;$i++) { $allValues[] = $i; }

        $results = array();
        foreach($allValues as $first) {
            foreach($allValues as $second) {
                // Avoid repetitive combination
                if ($second !== $first) {
                    $result = array($first, $second);
                    // remove order (in fact force it, to remove order's importance)
                    sort($result);
                    // Use key to avoid duplicate
                    $results[implode('-', $result)] = $result;
                }
            }
        }

        return $results;
    }

    /**
     * Merge two combination into one
     *
     * @param array[] $array1        The first list of combination
     * @param array[] $array2        The second list of combination
     * @param int     $expectingSize The number of element in the final combination
     *
     * @return array[]
     */
    protected static function combinationOfTwoArray($array1, $array2, $expectingSize) {
        $results = array();
        foreach($array1 as $first) {
            foreach($array2 as $second) {
                if ($second !== $first) {
                    // Merge the two combination
                    $result = array_merge($first, $second);
                    // Remove duplicate element
                    $result = array_unique($result);
                    // remove order (in fact force it, to remove order's importance)
                    sort($result);
                    // Use key to avoid duplicate combination
                    $results[implode('-', $result)] = $result;
                }
            }
        }

        // Filter the final array to only keep the combination of the wanted size
        return array_filter($results, function($item) use ($expectingSize) {
            return count($item) === $expectingSize;
        });
    }

    /**
     * Get all combination of size [$combinationSize] in set of [$itemCount] elements.
     *
     * @param int $itemCount       The number of element in the set
     * @param int $combinationSize The size of a combination
     *
     * @return array[]
     */
    public static function getCombination($itemCount, $combinationSize) {
        /*
         * The ideas is to recursively merge combination of size two to get the combination of our wanted size.
         * This way is easier to understand (use a more OOP approach, compare to other method),
         * and keep good enough performance.
         */

        // Get all combination of two element for our set
        $base = self::combinationOfTwo($itemCount);

        $growing = $base;
        for($i=3;$i<=$combinationSize;$i++) {
            $growing = self::combinationOfTwoArray($growing, $base, $i);
        }

        return $growing;
    }
}