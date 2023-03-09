<?php

namespace Drupal\test_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Render\Element\Form;
use Drupal\jsonapi\JsonApiResource\Data;

class EditTestForm extends FormBase{


  public function getFormId() {
    return 'test_form_edit';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $id = \Drupal::routeMatch()->getParameter('id');
    $query = Database::getConnection()->select('test_form','t')->fields('t',array('name','surname','email','telephone','birthday','subs_confirm'))
      ->condition('t.id',$id)->execute()->fetchAll();

    $data = $query[0];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
      '#default_value' => $data->name
    ]; // event participation form -> name

    $form['surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#required' => TRUE,
      '#default_value' => $data->surname
    ]; // event participation form -> surname

    $form['telephone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Telephone'),
      '#maxlength' => 11,
      '#required' => TRUE,
      '#default_value' => $data->telephone
    ]; // event participation form -> telephone

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail adress'),
      '#required' => TRUE,
      '#default_value' => $data->email
    ]; // event participation form -> email adress

    $form['birthday'] = [
      '#type' => 'date',
      '#title' => $this->t('Birthday'),
      '#default_value' => $data->birthday,
      '#required' => TRUE,
    ]; // event participation form -> date of birth

    $form['subs_confirm'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Subscription confirmation'),
      '#default_value' => $data->subs_confirm
    ]; // event participation form -> subscription confirmation status

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Edit'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $formFields = $form_state->getValues();

    $name         = trim($formFields['name']);
    $surname      = trim($formFields['surname']);
    $telephone    = trim($formFields['telephone']);
    $mail         = trim($formFields['email']);
    $birthday     = trim($formFields['birthday']);
    $subs_confirm = trim($formFields['subs_confirm']);

    if (!preg_match("/^([a-zA-Z' ]+)$/",$name) && isset($name) && !empty($name))
    {
      $form_state->setErrorByName('name', $this->t('Enter your name'));
    }
    if (!preg_match("/^([a-zA-Z' ]+)$/",$surname) && isset($surname) && !empty($surname))
    {
      $form_state->setErrorByName('surname', $this->t('Enter your surname'));
    }
    if (isset($telephone) && !empty($telephone))
    {
      if (!is_numeric($telephone))
      {
        $form_state->setErrorByName('telephone', $this->t('Enter only digits for the phone number.'));
      }else{
        if (mb_strlen($telephone) < 11)
        {
          $form_state->setErrorByName('telephone', $this->t('Please enter your complete phone information.'));
        }
      }
    }else{
      $form_state->setErrorByName('telephone',$this->t("Please enter your telephone"));
    }

    if (!isset($birthday) || empty($birthday))
    {
      $form_state->setErrorByName('birthday', $this->t('Please enter your date of birth'));
    }
    if (!isset($mail) || empty($mail) || !\Drupal::service('email.validator')->isValid($mail))
    {
      $form_state->setErrorByName('email', $this->t('Please enter your mail address'));
    }


  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $id = \Drupal::routeMatch()->getParameter('id');
      $conn  = Database::getConnection();
      $field = $form_state->getValues();

      $fields['name'] = $field['name'];
      $fields['surname'] = $field['surname'];
      $fields['telephone'] = $field['telephone'];
      $fields['email'] = $field['email'];
      $fields['birthday'] = $field['birthday'];
      $fields['subs_confirm'] = isset($field['subs_confirm']) && !empty($field['subs_confirm']) ? $field['subs_confirm'] : 0;

      $conn->update('test_form')->fields($fields)->condition('id',$id)->execute();
      $this->messenger()->addStatus($this->t('Update succesfull.'));
      $form_state->setRedirect('test_form.list');


    }
    catch (\Exception $e)
    {
      \Drupal::logger('log_test_form')->error($e->getMessage());

    }


  }
}