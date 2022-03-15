<?php


namespace nickdnk\ZeroBounce;

class Result
{

    const STATUS_ABUSE       = 'abuse';
    const STATUS_CATCH_ALL   = 'catch-all';
    const STATUS_DO_NOT_MAIL = 'do_not_mail';
    const STATUS_INVALID     = 'invalid';
    const STATUS_SPAMTRAP    = 'spamtrap';
    const STATUS_UNKNOWN     = 'unknown';
    const STATUS_VALID       = 'valid';

    const SUBSTATUS_ALIAS_ADDRESS               = 'alias_address';
    const SUBSTATUS_ANTISPAM_SYSTEM             = 'antispam_system';
    const SUBSTATUS_DISPOSABLE                  = 'disposable';
    const SUBSTATUS_DOES_NOT_ACCEPT_MAIL        = 'does_not_accept_mail';
    const SUBSTATUS_EXCEPTION_OCCURRED          = 'exception_occurred';
    const SUBSTATUS_FAILED_SMTP_CONNECTION      = 'failed_smtp_connection';
    const SUBSTATUS_FAILED_SYNTAX_CHECK         = 'failed_syntax_check';
    const SUBSTATUS_FORCIBLE_DISCONNECT         = 'forcible_disconnect';
    const SUBSTATUS_GLOBAL_SUPPRESSION          = 'global_suppression';
    const SUBSTATUS_GREYLISTED                  = 'greylisted';
    const SUBSTATUS_LEADING_PERIOD_REMOVED      = 'leading_period_removed';
    const SUBSTATUS_MAILBOX_NOT_FOUND           = 'mailbox_not_found';
    const SUBSTATUS_MAILBOX_QUOTA_EXCEEDED      = 'mailbox_quota_exceeded';
    const SUBSTATUS_MAIL_SERVER_DID_NOT_RESPOND = 'mail_server_did_not_respond';
    const SUBSTATUS_MAIL_SERVER_TEMPORARY_ERROR = 'mail_server_temporary_error';
    const SUBSTATUS_NO_DNS_ENTRIES              = 'no_dns_entries';
    const SUBSTATUS_POSSIBLE_TYPO               = 'possible_typo';
    const SUBSTATUS_POSSIBLE_TRAP               = 'possible_trap';
    const SUBSTATUS_ROLE_BASED                  = 'role_based';
    const SUBSTATUS_ROLE_BASED_CATCH_ALL        = 'role_based_catch_all';
    const SUBSTATUS_TIMEOUT_EXCEEDED            = 'timeout_exceeded';
    const SUBSTATUS_TOXIC                       = 'toxic';
    const SUBSTATUS_UNROUTABLE_IP_ADDRESS       = 'unroutable_ip_address';

    private $address, $status, $subStatus, $account, $domain, $didYouMean, $domainAgeDays, $freeEmail, $mxFound, $mxRecord, $smtpProvider, $user, $processedAt;

    public function __construct(string $address, string $status, string $subStatus, ?string $account, ?string $domain, ?string $didYouMean, ?int $domainAgeDays, bool $freeEmail, bool $mxFound, ?string $mxRecord, ?string $smtpProvider, User $user, string $processedAt)
    {

        $this->address = $address;
        $this->status = $status;
        $this->subStatus = $subStatus;
        $this->account = $account;
        $this->domain = $domain;
        $this->didYouMean = $didYouMean;
        $this->domainAgeDays = $domainAgeDays;
        $this->freeEmail = $freeEmail;
        $this->mxFound = $mxFound;
        $this->mxRecord = $mxRecord;
        $this->smtpProvider = $smtpProvider;
        $this->user = $user;
        $this->processedAt = $processedAt;
    }

    public function getAddress(): string
    {

        return $this->address;
    }

    public function getStatus(): string
    {

        return $this->status;
    }

    public function getSubStatus(): string
    {

        return $this->subStatus;
    }

    public function getAccount(): string
    {

        return $this->account;
    }

    public function getDomain(): ?string
    {

        return $this->domain;
    }

    public function getDidYouMean(): ?string
    {

        return $this->didYouMean;
    }

    public function getDomainAgeDays(): ?int
    {

        return $this->domainAgeDays;
    }

    public function isFreeEmail(): bool
    {

        return $this->freeEmail;
    }

    public function isMXFound(): bool
    {

        return $this->mxFound;
    }

    public function getMXRecord(): ?string
    {

        return $this->mxRecord;
    }

    public function getSMTPProvider(): ?string
    {

        return $this->smtpProvider;
    }

    public function getUser(): User
    {

        return $this->user;
    }

    public function getProcessedAt(): string
    {

        return $this->processedAt;
    }


}
