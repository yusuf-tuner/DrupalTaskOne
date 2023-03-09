<?php

namespace Drupal\eventform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
/**
 * Provides a EventForm form.
 */
class EventForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'eventform_event';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['event_form_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required' => TRUE,
    ]; // event participation form -> name

    $form['event_form_surname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#required' => TRUE,
    ]; // event participation form -> surname

    $form['event_form_phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Telephone'),
      '#required' => TRUE,
      '#maxlength' => 11
    ]; // event participation form -> phone

    $form['event_form_mail'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail'),
      '#required' => TRUE,
    ]; // event participation form -> mail adress

    $form['event_form_birthday'] = [
      '#type' => 'date',
      '#title' => $this->t('Date of birth'),
      '#date_date_format' => 'dd/mm/YY',
      '#default_value' => date('dd/mm/YY'),
      '#required' => TRUE

    ]; // event participation form -> date of birth

    $form['event_form_subsconfirm'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Subscription confirmation'),
      '#required' => TRUE,
    ]; // event participation form -> subscription confirmation

    $form['event_form_status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Participation status')
    ]; // event participation form -> participation status

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    $formFields = $form_state->getValues();

    $name        = trim($formFields['event_form_name']);
    $surname     = trim($formFields['event_form_surname']);
    $phone       = trim($formFields['event_form_phone']);
    $mail        = trim($formFields['event_form_mail']);
    $birthday    = trim($formFields['event_form_birthday']);
    $subsConfirm = isset($formFields['event_form_subsconfirm']) && !empty($formFields['event_form_subsconfirm']) ? $formFields['event_form_subsconfirm'] : 0;

    if (!preg_match("/^([a-zA-Z' ]+)$/",$name) && isset($name) && !empty($name))
    {
      $form_state->setErrorByName('event_form_name', $this->t('Enter your name'));
    }

    if (!preg_match("/^([a-zA-Z' ]+)$/",$surname) && isset($surname) && !empty($surname))
    {
      $form_state->setErrorByName('event_form_surname', $this->t('Enter your surname'));
    }

    if (isset($phone) && !empty($phone))
    {
      if (!is_numeric($phone))
      {
        $form_state->setErrorByName('event_form_phone', $this->t('Enter only digits for the phone number.'));
      }else{
        if (mb_strlen($phone) < 11)
        {
          $form_state->setErrorByName('event_form_phone', $this->t('Please enter your complete phone information.'));
        }
      }
    }else{
      $form_state->setErrorByName('event_form_phone',$this->t("Please enter your telephone"));
    }

    if (!isset($birthday) || empty($birthday))
    {
      $form_state->setErrorByName('event_form_birthday', $this->t('Please enter your date of birth'));
    }
    if (!isset($mail) || empty($mail) || !\Drupal::service('email.validator')->isValid($mail))
    {
      $form_state->setErrorByName('event_form_mail', $this->t('Please enter your mail address'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    try {
      $connection = Database::getConnection();
      $field = $form_state->getValues();

      $fields['event_form_name']     = $field['event_form_name'];
      $fields['event_form_surname']  = $field['event_form_surname'];
      $fields['event_form_phone']    = $field['event_form_phone'];
      $fields['event_form_mail']     = $field['event_form_mail'];
      $fields['event_form_birthday'] = $field['event_form_birthday'];
      $fields['event_form_subsconfirm'] = isset($field['event_form_subsconfirm']) && !empty($field['event_form_subsconfirm']) ? $field['event_form_subsconfirm'] : 0;
      $fields['event_form_status']      = isset($field['event_form_status']) && !empty($field['event_form_status']) ? $field['event_form_status'] : 0;

      $connection->insert('eventform')->fields($fields)->execute();
      $this->messenger()->addStatus($this->t('Your application for the event participation form has been received.'));
      $form_state->setRedirect('eventform.event');
    }
    catch (\Exception $e)
    {
      \Drupal::logger('log_eventform')->error($e->getMessage());
    }

  }


}
