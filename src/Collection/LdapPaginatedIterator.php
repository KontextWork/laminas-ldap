<?php

namespace Laminas\Ldap\Collection;

use Laminas\Ldap\Exception\LdapException;
use Laminas\Ldap\Ldap;

class LdapPaginatedIterator extends DefaultIterator {

  /** @noinspection PhpMissingParentConstructorInspection */
  public function __construct(Ldap $ldap, $entries) {
    if($entries == null){
      throw new LdapException($this->ldap, 'No entries given');
    }

    $this->entries = $entries;
    $this->ldap = $ldap;

    $this->setSortFunction('strnatcasecmp');
    $this->itemCount = count($entries);
  }

}