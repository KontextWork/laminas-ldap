<?php

namespace Laminas\Ldap\Collection;

use Laminas\Ldap\ErrorHandler;
use Laminas\Ldap\Exception\LdapException;
use Laminas\Ldap\Handler;
use Laminas\Ldap\Ldap;

class LdapPaginatedIterator extends DefaultIterator {
  /**
   * Only used to free the ldap results when we deconstruct.
   * @var []LDAP\Result
   */
  private $resultHandles;

  /** @noinspection PhpMissingParentConstructorInspection */
  public function __construct(Ldap $ldap, $entries, array $resultHandles) {
    if($entries == null){
      throw new LdapException($this->ldap, 'No entries given');
    }

    $this->entries = $entries;
    $this->ldap = $ldap;

    $this->setSortFunction('strnatcasecmp');
    $this->itemCount = count($entries);
    $this->resultHandles = $resultHandles;
  }

  /**
   * Called during deconstruction, overriding the implementation since we have several result handlers (per page)
   * not just one.
   *
   * @override
   * @return bool
   */
  public function close()
  {
    $isClosed = false;
    foreach($this->resultHandles as $resultHandle) {
      if ($resultHandle != null && Handler::isResultHandle($resultHandle)) {
        ErrorHandler::start();
        $isClosed       = ldap_free_result($resultHandle);
        ErrorHandler::stop();

        $this->current  = null;
      }
    }

    $this->resultHandles = [];

    return $isClosed;
  }
}
