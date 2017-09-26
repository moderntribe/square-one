<?php
namespace tad\FunctionMocker\Call\Verifier;


interface VerifierInterface
{

    /**
     * Checks if the function or method was called the specified number
     * of times.
     *
     * @param  int|string $times
     *
     * @return void
     */
    public function wasCalledTimes($times);

    /**
     * Checks if the function or method was called with the specified
     * arguments a number of times.
     *
     * @param  array $args
     * @param  int|string $times
     *
     * @return void
     */
    public function wasCalledWithTimes(array $args, $times);

    /**
     * Checks that the function or method was not called.
     *
     * @return void
     */
    public function wasNotCalled();

    /**
     * Checks that the function or method was not called with
     * the specified arguments.
     *
     * @param  array $args
     *
     * @return void
     */
    public function wasNotCalledWith(array $args);

    /**
     * Checks if a given function or method was called just one time.
     */
    public function wasCalledOnce();

    /**
     * Checks if a given function or method was called once with a set of arguments.
     *
     * @param array $args
     *
     * @return mixed
     */
    public function wasCalledWithOnce(array $args);
}
