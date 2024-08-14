<?php

namespace Drupal\drupal_components\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BusinessSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('drupal_components.settings');

    $form['extended_business_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Extended Business Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('extended_business_name'),
    ];

    $form['full_business_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Business Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('full_business_name'),
    ];

    $form['short_business_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Short Business Name'),
      '#required' => TRUE,
      '#default_value' => $config->get('short_business_name'),
    ];

    $form['business_address_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Business Address 1'),
      '#required' => TRUE,
      '#default_value' => $config->get('business_address_1'),
    ];

    $form['business_address_2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Business Address 2'),
      '#required' => TRUE,
      '#default_value' => $config->get('business_address_2'),
    ];

    $form['business_email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Business Email'),
      '#required' => TRUE,
      '#default_value' => $config->get('business_email'),
    ];

    $form['business_phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Business Phone'),
      '#required' => TRUE,
      '#default_value' => $config->get('business_phone'),
    ];

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('drupal_components.settings');
    $config->set('extended_business_name', $form_state->getValue('extended_business_name'));
    $config->set('full_business_name', $form_state->getValue('full_business_name'));
    $config->set('short_business_name', $form_state->getValue('short_business_name'));
    $config->set('business_address_1', $form_state->getValue('business_address_1'));
    $config->set('business_address_2', $form_state->getValue('business_address_2'));
    $config->set('business_email', $form_state->getValue('business_email'));
    $config->set('business_phone', $form_state->getValue('business_phone'));

    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'drupal_components.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'business_settings_form';
  }

}
