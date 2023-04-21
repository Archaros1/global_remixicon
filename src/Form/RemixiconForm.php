<?php

namespace Drupal\global_remixicon\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the remixicon entity edit forms.
 */
class RemixiconForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $entity = $this->getEntity();

    $message_arguments = ['%label' => $entity->toLink()->toString()];
    $logger_arguments = [
      '%label' => $entity->label(),
      'link' => $entity->toLink($this->t('View'))->toString(),
    ];

    switch ($result) {
      case SAVED_NEW:
        $this->messenger()->addStatus($this->t('New remixicon %label has been created.', $message_arguments));
        $this->logger('global_remixicon')->notice('Created new remixicon %label', $logger_arguments);
        break;

      case SAVED_UPDATED:
        $this->messenger()->addStatus($this->t('The remixicon %label has been updated.', $message_arguments));
        $this->logger('global_remixicon')->notice('Updated remixicon %label.', $logger_arguments);
        break;
    }

    $form_state->setRedirect('entity.remixicon.collection');

    return $result;
  }

}
