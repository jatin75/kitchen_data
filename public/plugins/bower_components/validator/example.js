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
					// notEmpty: {
					// 	message: 'Email address is required and can\'t be empty'
					// },
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

	$('#formAddDeliveryDateTime').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			deliveryDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Delivery date is required.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			deliveryTime: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Delivery time is required.'
					},
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
		}
	});

	$('#formAddInstallingDateTime').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			installationDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Installation Date is required.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			installationTime: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Installation Time is required.'
					},
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
			selectInstallationEmployees: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Installation Employee is required and can\'t be empty'
					},
				}
			},
		}
	});

	$('#formAddStoneInstallingDateTime').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			stoneInstallationDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Stone Installation Date is required.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			stoneInstallationTime: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Stone Installation Time is required.'
					},
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
			selectStoneInstallationEmployees: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Stone Installation Employee is required and can\'t be empty'
					},
				}
			},
		}
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
			employeePassword: {
				validators: {
					notEmpty: {
						message: 'Password is required and can\'t be empty'
					},
					/*stringLength: {
						min: 6,
						message: 'Ppassword should be of 6 digits.'
					},*/
				}
			},
		}
	});

	$('#formAddNote').bootstrapValidator({
		//excluded: ':disabled',
		fields: {
			jobNote: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Jobnote is required and can\'t be empty.'
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

	$('#formAddJob').bootstrapValidator({
		excluded: ':disabled',
		fields: {
			jobCompanyName: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Job company name is required and can\'t be empty'
					},
				}
			},
			comapnyClients: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Company client is required and can\'t be empty'
					},
				}
			},
			jobType: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Job status is required and can\'t be empty'
					},
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
			apartmentNo: {
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
						max: 7,
					}
				}
			},
			workingEmployee: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Working employee is required and can\'t be empty'
					},
				}
			},
			jobTitle: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Job title is required and can\'t be empty.'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Job title can only consist of alphanumeric.'
					}
				}
			},
			jobStatus: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Job status is required and can\'t be empty.'
					}
				}
			},
			salesEmployee: {
				trigger: 'change',
				validators: {
					regexp: {
						enabled: false,
					},
				}
			},
			jobStartDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Start date is required and can\'t be empty'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			jobEndDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Expected completion date is required and can\'t be empty'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			plumbingInstallationDate: {
				trigger: 'change',
				validators: {
					// notEmpty: {
					// 	message: 'Plumbing installation date is required and can\'t be empty'
					// },
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			deliveryDate: {
				trigger: 'change',
				validators: {
					// notEmpty: {
					// 	message: 'Delivery date is required.'
					// },
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			deliveryTime: {
				trigger: 'change',
				validators: {
					// notEmpty: {
					// 	message: 'Delivery time is required.'
					// },
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
			jobSuperName: {
				trigger: 'blur',
				validators: {
					notEmpty: {
						message: 'Job super name is required and can\'t be empty'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Job super name can only consist of alphanumeric.'
					}
				}
			},
			superPhoneNumber: {
				trigger: 'keyup',
				validators: {
					notEmpty: {
						message: 'Job super phone number is required and can\'t be empty'
					},
					stringLength: {
						min: 16,
						max: 16,
						message: 'Job super phone number should be of 10 digits.'
					},
					regexp: {
						regexp: /^\(?(\d{3})\)?[-\. ]?(\d{3})?[-\. ]?[-\. ]?[-\. ]?(\d{4})( x\d{4})?$/,
						message: 'Please enter valid Phone number.'
					}
				}
			},
			jobContractorName: {
				trigger: 'blur',
				validators: {
					// notEmpty: {
					// 	message: 'Job contractor name is required and can\'t be empty'
					// },
					regexp: {
						regexp: /^[a-zA-Z0-9\s]+$/i,
						message: 'Job contractor name can only consist of alphanumeric.'
					}
				}
			},
			contractorEmail: {
				trigger: 'blur',
				validators: {
					// notEmpty: {
					// 	message: 'Contractor email address is required and can\'t be empty'
					// },
					emailAddress: {
						message: 'Please enter valid email address.'
					}
				}
			},
			contractorPhoneNumber: {
				trigger: 'keyup',
				validators: {
					// notEmpty: {
					// 	message: 'Contractor phone number is required and can\'t be empty'
					// },
					stringLength: {
						min: 16,
						max: 16,
						message: 'Contractor phone number should be of 10 digits.'
					},
					regexp: {
						regexp: /^\(?(\d{3})\)?[-\. ]?(\d{3})?[-\. ]?[-\. ]?[-\. ]?(\d{4})( x\d{4})?$/,
						message: 'Please enter valid Phone number.'
					}
				}
			},
			deliveryInstallationSelect: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Delivery installation status is required and can\'t be empty'
					},
				}
			},
			installationSelect: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Installation status is required and can\'t be empty'
					},
				}
			},
			stoneInstallationSelect: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Stone installation status is required and can\'t be empty'
					},
				}
			},
			deliveryInstallationDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Date is required.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			deliveryInstallationTime: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Time is required.'
					},
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
			deliveryInstallationEmployees: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Delivery Installation Employee is required and can\'t be empty'
					},
				}
			},
			installationDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Date is required.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			installationTime: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Time is required.'
					},
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
			installationEmployees: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Installation Employee is required and can\'t be empty'
					},
				}
			},
			stoneInstallationDate: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Date is required.'
					},
					date: {
						format: 'MM/DD/YYYY',
						message: 'The date is not a valid'
					},
				}
			},
			stoneInstallationTime: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Time is required.'
					},
					callback: {
						callback: function (value, validator, $deliveryTime) {
							if (value.indexOf("A") == 5) {
								if (value < '09:00AM' || value > '11:59AM') {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else if (value.indexOf("P") == 5) {
								if ((value == '12:00PM') || ((value > '12:00PM') && (value <= '12:59PM'))) {
									return true;
								} else if ((value > '02:00PM')) {
									return {
										valid: false,
										message: 'Select between 09:00AM to 02:00PM'
									};
								} else {
									return true;
								}
							} else {
								return true;
							}
						}
					},
				}
			},
			stoneInstallationEmployees: {
				trigger: 'change',
				validators: {
					notEmpty: {
						message: 'Stone Installation Employee is required and can\'t be empty'
					},
				}
			},
		}
	});

	$('#adminPhoneNo').on('keyup', function () {
		$('#formAddAdmin').bootstrapValidator('revalidateField', 'adminPhoneNo');
	});
});