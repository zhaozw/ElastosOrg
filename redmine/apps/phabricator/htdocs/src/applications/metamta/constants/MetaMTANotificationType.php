<?php

final class MetaMTANotificationType
  extends MetaMTAConstants {

  const TYPE_DIFFERENTIAL_REVIEWERS      = 'differential-reviewers';
  const TYPE_DIFFERENTIAL_CLOSED         = 'differential-committed';
  const TYPE_DIFFERENTIAL_CC             = 'differential-cc';
  const TYPE_DIFFERENTIAL_COMMENT        = 'differential-comment';
  const TYPE_DIFFERENTIAL_UPDATED        = 'differential-updated';
  const TYPE_DIFFERENTIAL_REVIEW_REQUEST = 'differential-review-request';
  const TYPE_DIFFERENTIAL_OTHER          = 'differential-other';

  const TYPE_PHOLIO_STATUS            = 'pholio-status';
  const TYPE_PHOLIO_COMMENT           = 'pholio-comment';
  const TYPE_PHOLIO_UPDATED           = 'pholio-updated';
  const TYPE_PHOLIO_OTHER             = 'pholio-other';

}
