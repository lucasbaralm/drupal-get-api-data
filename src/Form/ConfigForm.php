<?php

namespace Drupal\getapidata\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an policy form.
 */
class ConfigForm extends FormBase {

  /**
   * Get the form id.
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * Build the form add policy.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $endpoint = \Drupal::config('api.settings')->get('api.endpoint');
    $attempt_number = \Drupal::config('api.settings')->get('api.attempt_number');
    $limit = \Drupal::config('api.settings')->get('api.limit');
    $sleep = \Drupal::config('api.settings')->get('api.sleep');
    $no_results_message = \Drupal::config('api.settings')->get('api.no_results_message');

    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = 0;
  
    if (isset($node)) {
      $nid = $node->id();
    }
    $form['endpoint'] = [
      '#title' => t('Insert Endpoint'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#default_value' => $endpoint,
    ];
    $form['attempt_number'] = [
      '#title' => t('Request attempts number'),
      '#type' => 'number',
      '#required' => TRUE,
      '#default_value' => $attempt_number,
    ];
    $form['limit'] = [
      '#title' => t('Limit records by request'),
      '#type' => 'number',
      '#required' => TRUE,
      '#default_value' => $limit,
    ];
    $form['sleep'] = [
      '#title' => t('Request Sleep time'),
      '#type' => 'number',
      '#required' => TRUE,
      '#default_value' => $sleep,
    ];
    $form['no_results_message'] = [
      '#title' => t('Insert no Results'),
      '#type' => 'textarea',
      '#rows' => 6,
      '#required' => TRUE,
      '#default_value' => $no_results_message,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Save'),
      '#attributes' => [
        'class' => [
          'button--primary',
        ],
      ],
    ];
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid,
    ];
    return $form;
  }

  /**
   * Set the value in settings.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    \Drupal::configFactory()->getEditable('api.settings')->set('api.endpoint', $form_state->getValue('endpoint'))->save();
    \Drupal::configFactory()->getEditable('api.settings')->set('api.attempt_number', $form_state->getValue('attempt_number'))->save();
    \Drupal::configFactory()->getEditable('api.settings')->set('api.limit', $form_state->getValue('limit'))->save();
    \Drupal::configFactory()->getEditable('api.settings')->set('api.sleep', $form_state->getValue('sleep'))->save();
    \Drupal::configFactory()->getEditable('api.settings')->set('api.no_results_message', $form_state->getValue('no_results_message'))->save();
    

    \Drupal::messenger()->addMessage(t('New parameters has been saved.'));
  }

}
