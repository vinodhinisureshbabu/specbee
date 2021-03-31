<?php

namespace Drupal\location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LocationDetails.
 */
class LocationDetails extends ConfigFormBase {

  /**
   * Drupal\Core\Config\CachedStorage definition.
   *
   * @var \Drupal\Core\Config\CachedStorage
   */
  protected $configStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->configStorage = $container->get('config.storage');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'location.location_details',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'location_details';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('location.location_details');
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Country Name'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('city'),
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => [
         'America/Chicago' => $this->t('America/Chicago'),
         'America/New_York' => $this->t('America/New_York'), 
         'Asia/Tokyo' => $this->t('Asia/Tokyo'),
         'Asia/Dubai' => $this->t('Asia/Dubai'), 
         'Asia/kolkatta' => $this->t('Asia/kolkatta'),
         'Europe/Aamsterdam' => $this->t('Europe/Aamsterdam'), 
         'Europe/Oslo' => $this->t('Europe/Oslo'), 
         'Europe/London' => $this->t('Europe/London')
       ],
      '#size' => 5,
      '#default_value' => $config->get('timezone'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('location.location_details')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->save();
  }

}
