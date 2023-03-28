<?php

namespace Drupal\news_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\news_form\Controller\NewsController;

class NewsForm extends FormBase{

  public function getFormId() {
    return "news_form_show";
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.
    $form['actions']['submit'] = ['#type' => 'submit','#value' => 'Data Pull'];

    return $form;
  }
  /**
   * (@inheritDoc)
   * */

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $newsController = new NewsController();
    $data   = $newsController->getNewsData();
    $insert = $newsController->importNews($data);
    if ($insert)
      $this->messenger()->addStatus($this->t("The news has been successfully recorded."));
    else
      $this->messenger()->addWarning($this->t('an error has occurred.'));

    drupal_flush_all_caches();
    $form_state->setRedirect('news_form.show');

  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

}