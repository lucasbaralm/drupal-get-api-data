<?php

/**
* @file
* Contains \Drupal\getapidata\GetDataController.
*/

namespace Drupal\getapidata\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Url as CoreUrl;


/**
 * Controller for RSVP List Report.
 */
class GetDataController extends ControllerBase {

  protected  $endpoint;
  protected  $attempt_number;
  protected  $limit;
  protected  $sleep;
  protected  $no_results_message;

  public function __construct() {
    
    $endpoint = \Drupal::config('api.settings')->get('api.endpoint');
    $attempt_number = \Drupal::config('api.settings')->get('api.attempt_number');
    $limit = \Drupal::config('api.settings')->get('api.limit');
    $sleep = \Drupal::config('api.settings')->get('api.sleep');
    $no_results_message = \Drupal::config('api.settings')->get('api.no_results_message');
    
    $this->endpoint = $endpoint;
    $this->attempt_number = $attempt_number;
    $this->limit = $limit;
    $this->sleep = $sleep;
    $this->no_results_message = $no_results_message;
  }



  public function pagerArray($items, $itemsPerPage) {
    // Get total items count.
    $total = count($items);
    // Get the number of the current page.
    $currentPage = \Drupal::service('pager.manager')->createPager($total, $itemsPerPage)->getCurrentPage();
    // Split an array into chunks.
    $chunks = array_chunk($items, $itemsPerPage);
    // Return current group item.
    $currentPageItems = $chunks[$currentPage];
    return $currentPageItems;
  }

  public function extractFacts($fact){
    return $fact['fact'];
  }

  public function getFacts () {
    
    $client = new Client();
    try {

      $attempts_remaining= $this->attempt_number;
      do {
      
      $response = $client->get($this->endpoint.'facts?limit='.$this->limit);
      if($response->getStatusCode() >= 200 and $response->getStatusCode() < 300){
        $attempts_remaining = 0;
      }else{
        $attempts_remaining-=1;
        sleep($this->sleep);
      }
    }while($attempts_remaining > 0);

      $currentPage = json_decode($response->getBody(),true)['data'];

      $total = 100;
      $num_per_page = 10;

    $rowPiece = $this->pagerArray($currentPage, 10);
    $header = array('Facts','Fact Size');

    $build['table'] = [
      '#id' => 'table',
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rowPiece,
      '#empty' => $this->t('No content has been found.'),
    ];

       $build['pager'] = [
        '#id'=>'pager',
      '#type' => 'pager',
    ];
    $build['#cache']['max-age'] = 0;

    $build['#attached']['library'][] ='getapidata/api';

      return $build;  
    }
    catch (RequestException $e) {
      \Drupal::logger('widget')->error($e->getMessage());
      $build = [
        '#markup' => $this->no_results_message,
        '#cache' => ['max-age' => 0],
      ];
      return $build; 
  }
}
  
  public function getFact () {
      
      $client = new Client();
      
      $factsListURL = CoreUrl::fromRoute('api.facts')->toString();

      $link_facts_list = t('<a href="@factsListURL">See more cat facts :)</a>', ['@factsListURL' => $factsListURL]);

    try {

      do {
      $response = $client->get($this->endpoint.'fact');
      if($response->getStatusCode() >= 200 and $response->getStatusCode() < 300){
        $attempts_remaining = 0;
      }else{
        $attempts_remaining-=1;
        sleep($this->sleep);
      }
    }while($attempts_remaining > 0);
    
      $currentPage = json_decode($response->getBody(), TRUE)['fact'];
      $build = [
        '#markup' => $currentPage . $link_facts_list,
        '#cache' => ['max-age' => 0],
      ];
      return $build;  
    }
    catch (RequestException $e) {
      \Drupal::logger('widget')->error($e->getMessage());
      $build = [
        '#markup' => $this->no_results_message,
        '#cache' => ['max-age' => 0],
      ];
      return $build; 
  }
  }
  
  public function getBreeds () {
    $client = new Client();
    try {

      $attempts_remaining= $this->attempt_number;
      do {
      
      $response = $client->get($this->endpoint.'breeds?limit='.$this->limit);
      if($response->getStatusCode() >= 200 and $response->getStatusCode() < 300){
        $attempts_remaining = 0;
      }else{
        $attempts_remaining-=1;
        sleep($this->sleep);
      }
    }while($attempts_remaining > 0);

      $currentPage = json_decode($response->getBody(),true)['data'];

      $total = 100;
      $num_per_page = 10;

    $rowPiece = $this->pagerArray($currentPage, 10);
    $header = array('Name','Country','Mixed','Hair', 'Color');

    $build['table'] = [
      '#id' => 'table',
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rowPiece,
      '#empty' => $this->t('No content has been found.'),
    ];

       $build['pager'] = [
        '#id'=>'pager',
      '#type' => 'pager',
    ];
    $build['#cache']['max-age'] = 0;

    $build['#attached']['library'][] ='getapidata/api';

      return $build;  
    }
    catch (RequestException $e) {
      \Drupal::logger('widget')->error($e->getMessage());
      $build = [
        '#markup' => $this->no_results_message,
        '#cache' => ['max-age' => 0],
      ];
      return $build; 
   }
  }
}