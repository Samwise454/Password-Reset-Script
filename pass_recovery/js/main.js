$(document).ready(() => {
	
	let switch_signup = $("#switch_signup");
	let switch_login = $("#switch_login");
	
	let signin_tab = $("#sign_in");
	let signup_tab = $("#sign_up");
	
	let login_btn = $("#login_btn");
	let signup_btn = $("#signup_btn");
	
	//error/succes display
	let e_note1 = $("#e_note1");
	let e_note2 = $("#e_note2");
	let e_note3 = $("#e_note3");
	
	//forgotten password elements
	let fpword_btn = $("#f_pword");
	let fpword_tab = $("#forg_pword");
	let repword_btn = $("#repword_btn");
	let back_login = $("#back_login");
	let back_signup = $("#back_signup");
	
	switch_login.click(() => {
		signin_tab.show("slow");
		signup_tab.hide("slow");
		e_note1.hide("slow");
	}) 
	
	switch_signup.click(() => {
		signin_tab.hide("slow");
		signup_tab.show("slow");
		e_note2.hide("slow");
	})
	
	fpword_btn.click(() => {
		signin_tab.hide("slow");
		e_note1.hide("slow");
		fpword_tab.show("slow");
		e_note3.hide("slow");
	})
	
	back_login.click(() => {
		fpword_tab.hide("slow");
		signin_tab.show("slow");
		e_note1.hide("slow");
		fpword_btn.hide("slow");
		e_note3.hide("slow");
	})
	
	back_signup.click(() => {
		fpword_tab.hide("slow");
		signup_tab.show("slow");
		e_note2.hide("slow");
		fpword_btn.hide("slow");
		e_note3.hide("slow");
	})
	
	//this code below fires when the recover password button is clicked
	repword_btn.click(() => {
		let rec_mail = $("#re_email").val();
		
		$.ajax({
			type: "POST",
			url: "scripts/recover_pass_script.php",
			data: {
				re_mail: rec_mail
			},	
			success: ((val) => {
				if (val === "inval") {
					e_note3.html("Invalid Input!!");
					e_note3.show("slow");
				}
				else {
					let val_split = val.split("(*)");
					let val1 = val_split[0];//this is the selector
					let val2 = val_split[1];//this is the validator
					let url = "http://localhost/pass_recovery/recover_pass.php?selector="+val1+"&validator="+val2;
					window.open(url);
				}
			})
		})
	})
	
	//I just chose to use ajax, you can use normal form login method via straight php
	
	login_btn.click(() => {//login button
		//for sign in values
		let signin_name = $("#uname").val();
		let signin_pword = $("#pword_signin").val();
		
		$.ajax({
			type: "POST",
			url: "scripts/sign_in.php",
			data: {
				l_uname: signin_name,
				l_pword: signin_pword
			},
			success: ((val) => {
				if (val === "inval") {
					e_note1.html("Invalid Input!!");
					e_note1.show("slow");
					fpword_btn.show("slow");
				}
				else {
					window.location.href = "http://localhost/pass_recovery/home.php?id=signedin";
				}
			})
		})
	})
	
	signup_btn.click(() => {//sign up button
		//for sign up values
		let signup_name = $("#uname_signup").val();
		let signup_pword = $("#pword_signup").val();
		let signup_repword = $("#re_pword").val();
		let email = $("#email").val();
		let tel = $("#tel").val();
		
		$.ajax({
			type: "POST",
			url: "scripts/sign_up.php",
			data: {
				s_uname: signup_name,
				s_pword: signup_pword,
				s_repword: signup_repword,
				email: email,
				tel: tel
			},
			success: ((val) => {
				if (val === "inval") {
					e_note2.html("Invalid Input!!");
					e_note2.show("slow");
				}
				else {
					window.location.href = "http://localhost/pass_recovery/home.php?id=signedup";
				}
			})
		})
	})
	
	//variables for recover_pass.php page
	let e_note5 = $("#e_note5");
	let new_pass_btn = $("#new_pass_btn");
	let new_back_btn = $("#go_back");
	
	new_pass_btn.click(() => {
		let new_pass1 = $("#new_pass1").val();
		let new_pass2 = $("#new_pass2").val();
		let selector = $("#selector").val();
		let validator = $("#validator").val();
		
		$.ajax({
			type: "POST",
			url: "scripts/new_pass_script.php",
			data: {
				pass1: new_pass1,
				pass2: new_pass2,
				select: selector,
				validate: validator
			},
			success: ((val) => {
				if (val === "inval") {
					e_note5.html("Invalid Input!!");
					e_note5.show("slow");
				}
				else if (val === "success") {
					window.location.href = "http://localhost/pass_recovery/index.php?id=resetsuccessful";
				}
				else {
					e_note5.html("Invalid Input!!");
					e_note5.show("slow");
				}
			})
		})
	})
})