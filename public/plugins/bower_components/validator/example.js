$(document).ready(function() {
	$('#loginForm').bootstrapValidator({
		fields: {
			admin_email: {
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
					stringLength: {
						min: 6,
						message: 'Current password should be of 6 digits.'
					},
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
			locationAddress:{
				validators:{
					notEmpty: {
						message:'Address is required and can\'t be empty.'
					}
				}
			},
			subAddress:{
				validators:{
					stringLength: {
						min: 0,
					}
				}
			},
			city:{
				validators:{
					stringLength: {
						min: 0,
					}
				}
			},
			state:{
				validators:{
					stringLength: {
						min: 0,
					}
				}
			},
			zipcode:{
				validators:{
					stringLength: {
						min: 0,
					}
				}
			}
		}
	});

	$('#companyPhoneNo').on('keyup', function() {
		$('#formAddClientCompany').bootstrapValidator('revalidateField', 'companyPhoneNo');
	});

	$('#formAddEmployee').bootstrapValidator({
		excluded: ':disabled',
		fields: {			
			employeeFirstName: {
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
			employeeEmail: {
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			employeePassword: {
				validators: {
					notEmpty: {
						message: 'Password is required and can\'t be empty'
					},
				}
			},
			employeeType:{
				validators:{
					notEmpty: {
						message:'Employee type is required and can\'t be empty.'
					}
				}
			},
		}
	});

	$('#employeePhoneNo').on('keyup', function() {
		$('#formAddEmployee').bootstrapValidator('revalidateField', 'employeePhoneNo');
	});

	$('#formImportProspect').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			importProspect: {
				validators: {
					notEmpty: {
						message: 'Please select file.'
					},
					file: {
						extension: 'csv',
						message: 'The selected file is not valid.'
					}
				}
			}
		}
	});

	$('#formImportSubscriber').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			importSubscriber: {
				validators: {
					notEmpty: {
						message: 'Please select file.'
					},
					file: {
						extension: 'csv',
						message: 'The selected file is not valid.'
					}
				}
			}
		}
	});

	$('#formImportAgreement').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			importAgreement: {
				validators: {
					notEmpty: {
						message: 'Please select file.'
					},
					file: {
						extension: 'pdf',
						message: 'The selected file is not valid.'
					}
				}
			}
		}
	});

	$('#formAddProspect').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			organizationName: {
				validators: {
					notEmpty: {
						message: 'Organization name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-z\s]+$/i,
						message: 'Organization name can only consist of alphabetical.'
					}
				}
			},
			accountEmail: {
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty.'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			firstName: {
				validators: {
					/*notEmpty: {
						message: 'First name is required and can\'t be empty.'
					},*/
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'First name can only consist of alphanumeric.'
					}
				}
			},
			lastName: {
				validators: {
					/*notEmpty: {
						message: 'Last name is required and can\'t be empty.'
					},*/
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Last name can only consist of alphanumeric.'
					}
				}
			},
			txtConsultant: {
				validators: {
					/*notEmpty: {
						message: 'Consultant is required and can\'t be empty.'
					},*/
				}
			},
			freeTrialEnd: {
				validators: {
					notEmpty: {
						message: 'Free trial-expired date is required and can\'t be empty.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			accountContactNo: {
				validators: {
					stringLength: {
						min: 16,
						max: 16,
						message: 'Phone number should be of 10 digits.'
					},
				}
			},
			claimStatus: {
				validators: {
					notEmpty: {
						message:'Account status is required and can\'t be empty.'
					},
				}
			},
		}
	});

	$('#freeTrialEnd').on('changeDate show', function() {
		$('#formAddProspect').bootstrapValidator('revalidateField', 'freeTrialEnd');
	});

	$('#accountContactNo').on('keyup', function() {
		$('#formAddProspect').bootstrapValidator('revalidateField', 'accountContactNo');
	});

	$('#editUserId').bootstrapValidator({
		fields: {
			userId:{
				validators:{
					notEmpty:{
						message:'User id is required and can\'t be empty.'
					},
					stringLength: {
						min: 8,
						message: 'User id must be of eight digits. '
					},
					regexp: {
						regexp: /^[a-zA-Z]{3}[0-9]{5}$/,
						message: 'User id can only consist of alphanumeric like ABC12345'
					}
				}
			}
		}
	});

	$('#formAddStaff').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			staffFirstName: {
				validators: {
					notEmpty: {
						message: 'First name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-z\s]+$/i,
						message: 'First name can only consist of alphabetical.'
					}
				}
			},
			staffLastName: {
				validators: {
					notEmpty: {
						message: 'Last name is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-z\s]+$/i,
						message: 'Last name can only consist of alphabetical.'
					}
				}
			},
			staffEmail: {
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty.'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			staffContactNo: {
				validators: {
					stringLength: {
						min: 16,
						max: 16,
						message: 'Phone number should be of 10 digits.'
					},
				}
			},
			staffDepartment: {
				validators: {
					notEmpty: {
						message:'Please select department.'
					}
				}
			},
			staffRoles: {
				validators: {
					notEmpty: {
						message:'Please select role.'
					}
				}
			},
			startDate: {
				validators: {
					notEmpty: {
						message: 'Start date is required and can\'t be empty.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
		}
	});

	$('#formAddSubscriber').bootstrapValidator({
		fields: {
			subscriberEmail: {
				validators: {
					notEmpty: {
						message: 'Email address is required and can\'t be empty.'
					},
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			/*firstName: {
				validators: {
					notEmpty: {
						message: 'First name is required and can\'t be empty'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'First name can only consist of alphanumeric'
					}
				}
			},*/
			/*lastName: {
				validators: {
					notEmpty: {
						message: 'Last name is required and can\'t be empty'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Last name can only consist of alphanumeric'
					}
				}
			}*/
		}
	});

	$('#formAddFeed').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			feedUrl: {
				validators: {
					notEmpty: {
						message: 'Feed url is required and can\'t be empty.'
					}
				}
			},
			feedCategory:{
				validators:{
					notEmpty: {
						message:'Please select sport.'
					}
				}
			}
		}
	});

	$('#addGameScore').bootstrapValidator({
		fields: {
			team_winhome: {
				validators: {
					notEmpty: {
						message: 'Score is required and can\'t be empty.'
					},
					stringLength: {
						max: 3,
						message: 'Score can be add upto three digits.'
					},
					regexp: {
						regexp: /^[0-9\s]+$/i,
						message: 'Score can only consist of numeric.'
					}
				}
			},
			team_winaway: {
				validators: {
					notEmpty: {
						message: 'Score is required and can\'t be empty.'
					},
					stringLength: {
						max: 3,
						message: 'Score can be add upto three digits.'
					},
					regexp: {
						regexp: /^[0-9\s]+$/i,
						message: 'Score can only consist of numeric.'
					}
				}
			},
			team_losthome: {
				validators: {
					notEmpty: {
						message: 'Score is required and can\'t be empty.'
					},
					stringLength: {
						max: 3,
						message: 'Score can be add upto three digits.'
					},
					regexp: {
						regexp: /^[0-9\s]+$/i,
						message: 'Score can only consist of numeric.'
					}
				}
			},
			team_lostaway:{
				validators:{
					notEmpty: {
						message:'Score is required and can\'t be empty.'
					},
					stringLength: {
						max: 3,
						message: 'Score can be add upto three digits.'
					},
					regexp: {
						regexp: /^[0-9\s]+$/i,
						message: 'Score can only consist of numeric.'
					}
				}
			}
		}
	});

	$('#formDepartment').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			departmentName: {
				validators: {
					notEmpty: {
						message: 'Department name is required and can\'t be empty.'
					}
				}
			}
		}
	});

	$('#formRole').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			roleName:{
				validators:{
					notEmpty: {
						message:'Role name is required and can\'t be empty.'
					}
				}
			},
			staffDepartment:{
				validators:{
					notEmpty: {
						message:'Please select department name.'
					}
				}
			}
		}
	});
});