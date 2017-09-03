<?php

return [
    '100'  => 'Operation was successful',
    '101'  => 'Operation was successful but PaymentVerification operation on this transaction have already been done',

    '-1'   => 'Information submitted is incomplete',
    '-2'   => 'Merchant ID or Acceptor IP is not correct',
    '-3'   => 'Amount should be above 100 Toman',
    '-4'   => 'Approved level of Acceptor is Lower than the silver',
    '-11'  => 'Request not found',
    '-12'  => 'Can not edit request',
    '-21'  => 'Financial operations for this transaction was not found',
    '-22'  => 'Transaction is unsuccessful',
    '-33'  => 'Transaction amount does not match the amount paid',
    '-34'  => 'Limit the number of transactions or number has crossed the divide',
    '-40'  => 'There is no access to the method',
    '-41'  => 'Additional Data related to information submitted is invalid',
    '-42'  => 'Payment token is invalid',
    '-54'  => 'Request archived',

    '-101' => 'Payment was unsuccessful',
    '-202' => 'Zarinpal did not respond',
    '-303' => 'Zarinpal did not respond',

];
