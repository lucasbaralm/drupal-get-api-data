<?php

namespace Drupal\getapidata\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

use Drupal\Component\Utility\Html;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use Drupal\getapidata\Controller\GetDataController;


/**
 * Provides an 'API' List Block.
 *
 * @Block(
 *  id = "api_block",
 *  admin_label = @Translation("API Block"),
 * )
 */
class FactBlock extends BlockBase {
  public function build() {

    $controller = new GetDataController;
    $rendering_in_block = $controller->getFact();
    return $rendering_in_block;

 }


  // /**
  //  * {@inheritdoc}
  //  */
  // public function build() {
  //   return \Drupal::formBuilder()->getForm('Drupal\apilist\Form\APIForm');
  // }

  // /**
  //  * Verify if the Account logged has Access Permission.
  //  *
  //  * @param Drupal\Core\Session\AccountInterface $account
  //  *   Logged account information.
  //  *
  //  * @return Drupal\Core\Access\AccessResult
  //  *   Return a AccesResult indicanting whether the access was successful.
  //  */
  // public function blockAccess(AccountInterface $account) {
  //   $node = \Drupal::routeMatch()->getParameter('node');
  //   /** @var \Drupal\apilist\EnablerService $enabler */
  //   $enabler = \Drupal::service('apilist.enabler');
  //   if ($node) {
  //     if ($enabler->isEnabled($node)) {
  //       return AccessResult::allowedIfHasPermission($account, 'view apilist');
  //     }
  //   }
  //   return AccessResult::forbidden();
  // }

}
