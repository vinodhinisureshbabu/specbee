<?php

namespace Drupal\location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\location\CurrentTimebasedonTimezone;

/**
 * Provides a 'DisplayLocation' block.
 *
 * @Block(
 *  id = "display_location",
 *  admin_label = @Translation("Display location"),
 * )
 */
class DisplayLocation extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Stores the location service.
   *
   * @var \Drupal\location\CurrentTimebasedonTimezone
   */
  protected $locationService;

  /**
   * Creates a SystemBrandingBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, CurrentTimebasedonTimezone $location_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->locationService = $location_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('location.current_time_based_on_timezone')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $location_config = $this->configFactory->get('location.location_details');
    $time  = $this->locationService->getCurrentTime($location_config->get('timezone')); 

    $build = [];
    $build['#theme'] = 'display_location';
    $build['#content']['country']['#markup'] = t('Current Country selected: @country',
      ['@country' => $location_config->get('country')]);
    $build['#content']['city']['#markup'] = t('Current city selected: @city',
      ['@city' => $location_config->get('city')]);
    $build['#content']['timezone']['#markup'] = t('Current timezone selected: @timezone',
      ['@timezone' => $location_config->get('timezone')]); 
    $build['#content']['current_time']['#markup'] = t('Current time is for choosen timezone: @time',['@time' => $time]);
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
