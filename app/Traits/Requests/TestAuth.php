<?php
namespace App\Traits\Requests;

use Illuminate\Validation\Rule;

trait TestAuth
{


  // ?todo rules of login for users
  protected function rulesLogin($requestField)
  {
    return [
      'login' => 'required|string|exists:users,' . $requestField,
      'password' => 'required|min:8|max:20',
    ];
  }

  // ?todo rules of users registers
  protected function rulesRegist()
  {
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,',
      'password' => 'nullable|min:8|max:20|confirmed',
      'phone' => 'required|string|max:12|regex:/^01[0125][0-9]{8}$/',
    ];
  }


  // ?todo rules of Change pass for users
  protected function ruleChangePass()
  {
    return [
      'password' => 'required|confirmed|min:8',
      'id' => 'required|exists:users,id'
    ];
  }


  // ?todo rules update users
  protected function rulesUpdateUsers()
  {
    return [
      'name' => 'required|min:4|max:20',
      'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
      'phone' => 'required|numeric|digits:10',
      'password' => 'sometimes|min:8',
    ];

  }


  // ?todo rules show bydate readings machines
  protected function rulesdate()
  {
    return [
      "created_at" => "required|date",
    ];
  }


  // ?todo rules store comments 
  protected function rulesComment()
  {
    return [
      'posts_id' => 'required|exists:posts,id',
      'comment' => 'required|min:5|max:200',
    ];
  }

}