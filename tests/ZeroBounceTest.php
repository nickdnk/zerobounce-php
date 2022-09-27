<?php


namespace nickdnk\ZeroBounce\Tests;

use nickdnk\ZeroBounce\ConnectionException;
use nickdnk\ZeroBounce\Email;
use nickdnk\ZeroBounce\Result;
use nickdnk\ZeroBounce\HttpException;
use nickdnk\ZeroBounce\ZeroBounce;
use PHPUnit\Framework\TestCase;

class ZeroBounceTest extends TestCase
{

    /** @var ZeroBounce */
    private static $handler;

    public static function setUpBeforeClass(): void
    {

        self::$handler = new ZeroBounce(getenv('ZEROBOUNCE_API_KEY'));

    }

    public function testValid()
    {

        $result = self::$handler->validateEmail(new Email('valid@example.com', null));
        $this->assertEquals(Result::STATUS_VALID, $result->getStatus());

        $result = self::$handler->validateEmail(new Email('leading_period_removed@example.com', null));
        $this->assertEquals(Result::STATUS_VALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_LEADING_PERIOD_REMOVED, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('free_email@example.com', null));
        $this->assertEquals(Result::STATUS_VALID, $result->getStatus());
        $this->assertTrue($result->isFreeEmail());

    }

    public function testAbuse()
    {

        $result = self::$handler->validateEmail(new Email('abuse@example.com', null));
        $this->assertEquals(Result::STATUS_ABUSE, $result->getStatus());
    }

    public function testCatchAll()
    {

        $result = self::$handler->validateEmail(new Email('catch_all@example.com', null));
        $this->assertEquals(Result::STATUS_CATCH_ALL, $result->getStatus());
    }

    public function testInvalid()
    {

        $result = self::$handler->validateEmail(new Email('does_not_accept_mail@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_DOES_NOT_ACCEPT_MAIL, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('invalid@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_MAILBOX_NOT_FOUND, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('mailbox_not_found@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_MAILBOX_NOT_FOUND, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('failed_syntax_check@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());

        $result = self::$handler->validateEmail(new Email('mailbox_quota_exceeded@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_MAILBOX_QUOTA_EXCEEDED, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('no_dns_entries@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_NO_DNS_ENTRIES, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('possible_typo@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_POSSIBLE_TYPO, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('unroutable_ip_address@example.com', null));
        $this->assertEquals(Result::STATUS_INVALID, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_UNROUTABLE_IP_ADDRESS, $result->getSubStatus());

    }

    public function testUnknown()
    {

        $result = self::$handler->validateEmail(new Email('antispam_system@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_ANTISPAM_SYSTEM, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('exception_occurred@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_EXCEPTION_OCCURRED, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('failed_smtp_connection@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_FAILED_SMTP_CONNECTION, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('forcible_disconnect@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_FORCIBLE_DISCONNECT, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('greylisted@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_GREYLISTED, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('mail_server_did_not_respond@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_MAIL_SERVER_DID_NOT_RESPOND, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('unknown@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_MAIL_SERVER_TEMPORARY_ERROR, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('timeout_exceeded@example.com', null));
        $this->assertEquals(Result::STATUS_UNKNOWN, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_TIMEOUT_EXCEEDED, $result->getSubStatus());

    }

    public function testSpamTrap()
    {

        $result = self::$handler->validateEmail(new Email('spamtrap@example.com', null));
        $this->assertEquals(Result::STATUS_SPAMTRAP, $result->getStatus());

    }

    public function testDoNotEmail()
    {

        $result = self::$handler->validateEmail(new Email('toxic@example.com', null));
        $this->assertEquals(Result::STATUS_DO_NOT_MAIL, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_TOXIC, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('disposable@example.com', null));
        $this->assertEquals(Result::STATUS_DO_NOT_MAIL, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_DISPOSABLE, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('global_suppression@example.com', null));
        $this->assertEquals(Result::STATUS_DO_NOT_MAIL, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_GLOBAL_SUPPRESSION, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('possible_trap@example.com', null));
        $this->assertEquals(Result::STATUS_DO_NOT_MAIL, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_POSSIBLE_TRAP, $result->getSubStatus());

        $result = self::$handler->validateEmail(new Email('role_based@example.com', null));
        $this->assertEquals(Result::STATUS_DO_NOT_MAIL, $result->getStatus());
        $this->assertEquals(Result::SUBSTATUS_ROLE_BASED, $result->getSubStatus());

    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws HttpException
     */
    public function testException()
    {

        $this->expectException(HttpException::class);

        $handler = new ZeroBounce('invalid_key');

        $handler->validateEmail(new Email('whatever@domain.com', null));

    }

}
