<?php

namespace Drupal\drupal_components\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'topBarContactBlock' block.
 *
 * @Block(
 *  id = "custom_logo_block",
 *  admin_label = @Translation("Custom Logo Block"),
 * )
 */
class CustomLogoBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
    // Get the theme.

    $form['site_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Site Name'),
      '#description' => t('Enter the site name'),
      '#default_value' => $this->configuration['site_name'] ?? '',
    );

    $form['site_slogan'] = array(
      '#type' => 'textfield',
      '#title' => t('Site Slogan'),
      '#description' => t('Enter the site slogan'),
      '#default_value' => $this->configuration['site_slogan'] ?? '',
    );

    $form['logo_image_solid'] = array(
      '#type' => 'managed_file',
      '#upload_location' => 'public://logo',
      '#title' => t('Solid Image'),
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png gif']
      ],
      '#default_value' => $this->configuration['logo_image_solid'] ?? '',
      '#description' => t('The image to display'),
      //'#required' => true
    );

    $form['logo_image_trans'] = array(
      '#type' => 'managed_file',
      '#upload_location' => 'public://logo',
      '#title' => t('Transparent Image'),
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png gif']
      ],
      '#default_value' => $this->configuration['logo_image_trans'] ?? '',
      '#description' => t('The image to display'),
      //'#required' => true
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    $this->configuration['site_name'] = $form_state->getValue('site_name');
    $this->configuration['site_slogan'] = $form_state->getValue('site_slogan');

    $solid_image = $form_state->getValue('logo_image_solid');
    if ($solid_image != $this->configuration['logo_image_solid']) {
      if (!empty($solid_image[0])) {
        $file = File::load($solid_image[0]);
        $file->setPermanent();
        $file->save();
        $file_usage = \Drupal::service('file.usage');
        $file_usage->add($file, 'drupal_components', 'file', \Drupal::currentUser()->id());
      }
    }
    $trans_image = $form_state->getValue('logo_image_trans');
    if ($trans_image != $this->configuration['logo_image_trans']) {
      if (!empty($trans_image[0])) {
        $file = File::load($trans_image[0]);
        $file->setPermanent();
        $file->save();
        $file_usage = \Drupal::service('file.usage');
        $file_usage->add($file, 'drupal_components', 'file', \Drupal::currentUser()->id());
      }
    }

    $this->configuration['logo_image_solid'] = $form_state->getValue('logo_image_solid');
    $this->configuration['logo_image_trans'] = $form_state->getValue('logo_image_trans');

  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $site_name = $this->configuration['site_name'];
    $site_slogan = $this->configuration['site_slogan'];
    $logo_solid = $this->configuration['logo_image_solid'];
    $logo_trans = $this->configuration['logo_image_trans'];

    $style = ImageStyle::load('logo');

    if (!empty($logo_solid[0])) {
      if ($solid_file = File::load($logo_solid[0])) {
        $solid_image_path = $style->buildUrl($solid_file->getFileUri());
      }
    }

    if (!empty($logo_trans[0])) {
      if ($trans_file = File::load($logo_trans[0])) {
        $trans_image_path = $style->buildUrl($trans_file->getFileUri());
      }
    }

    return [
      '#theme' => 'custom_logo_block',
      '#site_name' => $site_name,
      '#site_slogan' => $site_slogan,
      '#logo_image_solid' => $solid_image_path,
      '#logo_image_trans' => $trans_image_path,
    ];
  }

}
