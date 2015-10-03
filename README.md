# Math library

A set of class and function around Mathematics.

## What inside ?

### CombinationBuilder

An utility class to create combination.
The class also provide a factorial calculation.

## Example and Usage

### Combination

Get for 3 in 5:

```php
print_r(CombinationBuilder::getCombination(5, 3));
```

Will output:

```
Array
(
    [1-2-3] => Array
        (
            [0] => 1
            [1] => 2
            [2] => 3
        )

    [1-2-4] => Array
        (
            [0] => 1
            [1] => 2
            [2] => 4
        )

    [1-2-5] => Array
        (
            [0] => 1
            [1] => 2
            [2] => 5
        )

    [1-3-4] => Array
        (
            [0] => 1
            [1] => 3
            [2] => 4
        )

    [1-3-5] => Array
        (
            [0] => 1
            [1] => 3
            [2] => 5
        )

    [1-4-5] => Array
        (
            [0] => 1
            [1] => 4
            [2] => 5
        )

    [2-3-4] => Array
        (
            [0] => 2
            [1] => 3
            [2] => 4
        )

    [2-3-5] => Array
        (
            [0] => 2
            [1] => 3
            [2] => 5
        )

    [2-4-5] => Array
        (
            [0] => 2
            [1] => 4
            [2] => 5
        )

    [3-4-5] => Array
        (
            [0] => 3
            [1] => 4
            [2] => 5
        )

)
```

Or in a more compact way:

```
(1, 2, 3)
(1, 2, 4)
(1, 2, 5)
(1, 3, 4)
(1, 3, 5)
(1, 4, 5)
(2, 3, 4)
(2, 3, 5)
(2, 4, 5)
(3, 4, 5)
```

### Combination Count

Get the number of possible combination

```
print_r(CombinationBuilder::getCombinationCount(5, 3));
```

Will output:

```
10
```

### Factorial

Get the factorial of 5 (`5!`)

```
print_r(CombinationBuilder::factorial(5));
```

Will output:

```
120
```