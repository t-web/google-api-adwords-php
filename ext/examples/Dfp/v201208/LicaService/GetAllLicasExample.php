<?php
/**
 * This example gets all line item creative associations (LICA). To create
 * LICAs, run CreateLicasExample.php or
 * AssociateCreativeSetToLineItemExample.php
 *
 * Tags: LineItemCreativeAssociationService.getLineItemCreativeAssociationsByStatement
 *
 * PHP version 5
 *
 * Copyright 2012, Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    GoogleApiAdsDfp
 * @subpackage v201208
 * @category   WebServices
 * @copyright  2012, Google Inc. All Rights Reserved.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License,
 *             Version 2.0
 * @author     Vincent Tsao
 */
error_reporting(E_STRICT | E_ALL);

// You can set the include path to src directory or reference
// DfpUser.php directly via require_once.
// $path = '/path/to/dfp_api_php_lib/src';
$path = dirname(__FILE__) . '/../../../../src';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'Google/Api/Ads/Dfp/Lib/DfpUser.php';
require_once dirname(__FILE__) . '/../../../Common/ExampleUtils.php';

try {
  // Get DfpUser from credentials in "../auth.ini"
  // relative to the DfpUser.php file's directory.
  $user = new DfpUser();

  // Log SOAP XML request and response.
  $user->LogDefaults();

  // Get the LineItemCreativeAssociationService.
  $licaService =
      $user->GetService('LineItemCreativeAssociationService', 'v201208');

  // Set defaults for page and statement.
  $page = new LineItemCreativeAssociationPage();
  $filterStatement = new Statement();
  $offset = 0;

  do {
    // Create a statement to get all LICAs.
    $filterStatement->query = 'LIMIT 500 OFFSET ' . $offset;

    // Get LICAs by statement.
    $page = $licaService->getLineItemCreativeAssociationsByStatement(
        $filterStatement);

    // Display results.
    if (isset($page->results)) {
      $i = $page->startIndex;
      foreach ($page->results as $lica) {
        if (isset($lica->creativeSetId)) {
          printf("%d) LICA with line item ID %d, creative set ID %d, and " .
              "status %s was found.\n", $i, $lica->lineItemId,
              $lica->creativeSetId, $lica->status);
        } else {
          printf("%d) LICA with line item ID %d, creative ID %d, and status " .
              "%s was found.\n", $i, $lica->lineItemId, $lica->creativeId,
              $lica->status);
        }
        $i++;
      }
    }

    $offset += 500;
  } while ($offset < $page->totalResultSetSize);

  print 'Number of results found: ' . $page->totalResultSetSize . "\n";
} catch (OAuth2Exception $e) {
  ExampleUtils::CheckForOAuth2Errors($e);
} catch (ValidationException $e) {
  ExampleUtils::CheckForOAuth2Errors($e);
} catch (Exception $e) {
  print $e->getMessage() . "\n";
}

