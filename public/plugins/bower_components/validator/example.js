$(document).ready(function () {
	$('#loginForm').bootstrapValidator({
		fields: {
			admin_email: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			admin_password: {
				trigger: 'keyup',
				validators: {
					notEmpty: {
						message: 'Password is required and can\'t be empty'
					},
				}
			}
		}
	});

	$('#formForgotPassword').bootstrapValidator({
		fields: {
			txtemail: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			}
		}
	});

	$('#formAdmin').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			editProfilePic: {
				validators: {
					notEmpty: {
						message: 'Please select file.'
					},
					file: {
						extension: 'jpeg,jpg,png',
						message: 'The selected file is not valid.'
					}
				}
			}
		}
	});

	$('#formAccountSetting').bootstrapValidator({
		fields: {
			currentPassword: {
				validators: {
					notEmpty: {
						message: 'Current password is required and can\'t be empty'
					},
					/*stringLength: {
						min: 6,
						message: 'Current password should be of 6 digits.'
					},*/
				}
			},
			newPassword: {
				validators: {
					notEmpty: {
						message: 'New password is required and can\'t be empty'
					},
					stringLength: {
						min: 6,
						message: 'New password should be of 6 digits.'
					},
				}
			},
			retypePassword: {
				validators: {
					notEmpty: {
						message: 'Retype password is required and can\'t be empty'
					},
					stringLength: {
						min: 6,
						message: 'Retype password should be of 6 digits.'
					},
				}
			},
		}
	});

	$('#formAddClientCompany').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			companyName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Company name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-z\s]+$/i,
						message: 'Company name can only consist of alphabetical.'
					}
				}
			},
			companyEmail: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			companyPhoneNo: {
				trigger: 'keyup',
				validators: {
					notEmpty: {
						message: 'Phone number is required and can\'t be empty'
					},
					stringLength: {
						min: 16,
						max: 16,
						message: 'Phone number should be of 10 digits.'
					},
					regexp: {
						regexp: /^\(?(\d{3})\)?[-\. ]?(\d{3})?[-\. ]?[-\. ]?[-\. ]?(\d{4})( x\d{4})?$/,
						message: 'Please enter valid Phone number.'
					}
				}
			},
			locationAddress: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Address is required and can\'t be empty.'
					}
				}
			},
			subAddress: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			city: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			state: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			zipcode: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			}
		}
	});

	$('#companyPhoneNo').on('keyup', function () {
		$('#formAddClientCompany').bootstrapValidator('revalidateField', 'companyPhoneNo');
	});

	$('#formAddEmployee').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			employeeFirstName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Employee first name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Employee first name can only consist of alphanumeric.'
					}
				}
			},
			employeeLastName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Employee last name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Employee last name can only consist of alphanumeric.'
					}
				}
			},
			employeePhoneNo: {
				trigger: 'keyup',
				validators: {
					notEmpty: {
						message: 'Phone number is required and can\'t be empty'
					},
					stringLength: {
						min: 16,
						max: 16,
						message: 'Phone number should be of 10 digits.'
					},
					regexp: {
						regexp: /^\(?(\d{3})\)?[-\. ]?(\d{3})?[-\. ]?[-\. ]?[-\. ]?(\d{4})( x\d{4})?$/,
						message: 'Please enter valid Phone number.'
					}
				}
			},
			employeeEmail: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			employeeType: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Employee type is required and can\'t be empty.'
					}
				}
			},
		}
	});

	$('#employeePhoneNo').on('keyup', function () {
		$('#formAddEmployee').bootstrapValidator('revalidateField', 'employeePhoneNo');
	});

	$('#formAddClient').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			clientFirstName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Client first name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Client first name can only consist of alphanumeric.'
					}
				}
			},
			clientLastName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Client last name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Client last name can only consist of alphanumeric.'
					}
				}
			},
			clientContactNo: {
				trigger: 'keyup',
				validators: {
					notEmpty: {
						message: 'Phone number is required and can\'t be empty'
					},
					stringLength: {
						min: 16,
						max: 16,
						message: 'Phone number should be of 10 digits.'
					},
					regexp: {
						regexp: /^\(?(\d{3})\)?[-\. ]?(\d{3})?[-\. ]?[-\. ]?[-\. ]?(\d{4})( x\d{4})?$/,
						message: 'Please enter valid Phone number.'
					}
				}
			},
			clientEmail: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			clientCompany: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Client company is required and can\'t be empty.'
					}
				}
			},
			locationAddress: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Address 1 is required and can\'t be empty.'
					}
				}
			},
			subAddress: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			city: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			state: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			zipcode: {
				validators: {
					stringLength: {
						min: 0,
					}
				}
			},
			contactPreference: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Contact Preference is required and can\'t be empty.'
					}
				}
			},
		}
	});

	$('#clientContactNo').on('keyup', function () {
		$('#formAddClient').bootstrapValidator('revalidateField', 'clientContactNo');
	});

	$('#formAddAdmin').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			adminFirstName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Admin first name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Admin first name can only consist of alphanumeric.'
					}
				}
			},
			adminLastName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Admin last name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Admin last name can only consist of alphanumeric.'
					}
				}
			},
			adminPhoneNo: {
				trigger: 'keyup',
				validators: {
					notEmpty: {
						message: 'Phone number is required and can\'t be empty'
					},
					stringLength: {
						min: 16,
						max: 16,
						message: 'Phone number should be of 10 digits.'
					}
				}
			},
			adminEmail: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
		}
	});

	$('#adminPhoneNo').on('keyup', function () {
		$('#formAddAdmin').bootstrapValidator('revalidateField', 'adminPhoneNo');
	});



});