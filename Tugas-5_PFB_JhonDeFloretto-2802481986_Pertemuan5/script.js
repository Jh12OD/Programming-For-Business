document
  .getElementById("registrationForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    resetErrors();

    const isFullNameValid = validateFullName();
    const isEmailValid = validateEmail();
    const isPasswordValid = validatePassword();
    const isConfirmPasswordValid = validateConfirmPassword();
    const isPhoneValid = validatePhone();
    const isGenderValid = validateGender();
    const isHobbiesValid = validateHobbies();
    const isCountryValid = validateCountry();

    if (
      isFullNameValid &&
      isEmailValid &&
      isPasswordValid &&
      isConfirmPasswordValid &&
      isPhoneValid &&
      isGenderValid &&
      isHobbiesValid &&
      isCountryValid
    ) {
      alert("Registration successful! Form is valid and ready for submission.");
    }
  });

function resetErrors() {
  const inputs = document.querySelectorAll(".input-text, .select");
  inputs.forEach((input) => {
    input.classList.remove("error");
  });

  const errorMessages = document.querySelectorAll(".error-message");
  errorMessages.forEach((error) => {
    error.textContent = "";
  });
}

function validateFullName() {
  const fullName = document.getElementById("fullName").value.trim();
  const errorElement = document.getElementById("fullNameError");

  if (fullName === "") {
    showError("fullName", "Full name is required");
    return false;
  }

  if (fullName.length < 2) {
    showError("fullName", "Full name must be at least 2 characters long");
    return false;
  }

  return true;
}

function validateEmail() {
  const email = document.getElementById("email").value.trim();
  const errorElement = document.getElementById("emailError");
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (email === "") {
    showError("email", "Email is required");
    return false;
  }

  if (!emailRegex.test(email)) {
    showError("email", "Please enter a valid email address");
    return false;
  }

  return true;
}

function validatePassword() {
  const password = document.getElementById("password").value;
  const errorElement = document.getElementById("passwordError");

  if (password === "") {
    showError("password", "Password is required");
    return false;
  }

  if (password.length < 6) {
    showError("password", "Password must be at least 6 characters long");
    return false;
  }

  return true;
}

function validateConfirmPassword() {
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const errorElement = document.getElementById("confirmPasswordError");

  if (confirmPassword === "") {
    showError("confirmPassword", "Please confirm your password");
    return false;
  }

  if (password !== confirmPassword) {
    showError("confirmPassword", "Passwords do not match");
    return false;
  }

  return true;
}

function validatePhone() {
  const phone = document.getElementById("phoneNumber").value.trim();
  const errorElement = document.getElementById("phoneNumberError");
  const phoneRegex = /^\d+$/;

  if (phone === "") {
    showError("phoneNumber", "Phone number is required");
    return false;
  }

  if (!phoneRegex.test(phone)) {
    showError("phoneNumber", "Phone number must contain only numbers");
    return false;
  }

  if (phone.length < 10) {
    showError("phoneNumber", "Phone number must be at least 10 digits");
    return false;
  }

  return true;
}

function validateGender() {
  const gender = document.querySelector('input[name="gender"]:checked');
  const errorElement = document.getElementById("genderError");

  if (!gender) {
    showError("gender", "Please select your gender");
    return false;
  }

  return true;
}

function validateHobbies() {
  const hobbies = document.querySelectorAll('input[name="hobbies"]:checked');
  const errorElement = document.getElementById("hobbiesError");

  if (hobbies.length === 0) {
    showError("hobbies", "Please select at least one hobby");
    return false;
  }

  return true;
}

function validateCountry() {
  const country = document.getElementById("country").value;
  const errorElement = document.getElementById("countryError");

  if (country === "") {
    showError("country", "Please select your country");
    return false;
  }

  return true;
}

function showError(fieldName, message) {
  const field = document.getElementById(fieldName);
  const errorElement = document.getElementById(fieldName + "Error");

  if (field) {
    field.classList.add("error");
  }

  if (errorElement) {
    errorElement.textContent = message;
  }
}

document.querySelectorAll(".input-text, .select").forEach((input) => {
  input.addEventListener("blur", function () {
    validateField(this.id);
  });
});

document
  .querySelectorAll('input[type="radio"], input[type="checkbox"]')
  .forEach((input) => {
    input.addEventListener("change", function () {
      if (this.name === "gender") {
        validateGender();
      } else if (this.name === "hobbies") {
        validateHobbies();
      }
    });
  });

function validateField(fieldId) {
  switch (fieldId) {
    case "fullName":
      validateFullName();
      break;
    case "email":
      validateEmail();
      break;
    case "password":
      validatePassword();
      break;
    case "confirmPassword":
      validateConfirmPassword();
      break;
    case "phoneNumber":
      validatePhone();
      break;
    case "country":
      validateCountry();
      break;
  }
}
