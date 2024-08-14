<?php

namespace Drupal\drupal_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'FooterContactBlock' block.
 *
 * @Block(
 *  id = "footer_contact_block",
 *  admin_label = @Translation("Footer Contact Block"),
 * )
 */
class FooterContactBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

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
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {


  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $components_config = $this->configFactory->get('drupal_components.settings');


    return [
      '#theme' => 'footer_contact_block',
      '#full_business_name' => $components_config->get('full_business_name'),
      '#short_business_name' => $components_config->get('short_business_name'),
      '#business_address_1' => $components_config->get('business_address_1'),
      '#business_address_2' => $components_config->get('business_address_2'),
      '#business_email' => $components_config->get('business_email'),
      '#business_phone' => $components_config->get('business_phone'),
    ];
  }

}
