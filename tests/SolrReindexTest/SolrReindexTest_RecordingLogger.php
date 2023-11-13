<?php

namespace SilverStripe\FullTextSearch\Tests\SolrReindexTest;

use Monolog\Logger;
use SilverStripe\Dev\TestOnly;
use SilverStripe\FullTextSearch\Tests\SolrReindexTest\SolrReindexTest_Handler;

/**
 * Test logger for recording messages
 */
class SolrReindexTest_RecordingLogger extends Logger implements TestOnly
{
    /**
     * @var SolrReindexTest_Handler
     */
    protected $testHandler = null;

    public function __construct($name = 'testlogger', array $handlers = array(), array $processors = array())
    {
        parent::__construct($name, $handlers, $processors);

        $this->testHandler = new SolrReindexTest_Handler();
        $this->pushHandler($this->testHandler);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->testHandler->getMessages();
    }

    /**
     * Clear all messages
     */
    public function clear()
    {
        $this->testHandler->clear();
    }

    /**
     * Get messages with the given filter
     *
     * @param string $containing
     * @return array Filtered array
     */
    public function filterMessages($containing)
    {
        return array_values(array_filter(
            $this->getMessages() ?? [],
            function ($content) use ($containing) {
                return stripos($content ?? '', $containing ?? '') !== false;
            }
        ));
    }

    /**
     * Count all messages containing the given substring
     *
     * @param string $containing Message to filter by
     * @return int
     */
    public function countMessages($containing = null)
    {
        if ($containing) {
            $messages = $this->filterMessages($containing);
        } else {
            $messages = $this->getMessages();
        }
        return count($messages ?? []);
    }
}
