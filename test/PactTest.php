<?php namespace Test;

use PHPUnit\Framework\TestCase;
use Wit\Pact;

/**
 * 
 */
final class PactTest extends TestCase {
    /**
     * 
     */
    public function testNew() {
        $this->assertInstanceOf(
            Pact::class, new Pact(__DIR__)
        );
    }
}