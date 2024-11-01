let showPassword = (type, ele) => {
  document.getElementById(type).type =
    document.getElementById(type).type == "password" ? "text" : "password";
  let icon = ele.childNodes[0].classList;
  let stringIcon = icon.toString();
  if (stringIcon.includes("fa fa-eye")) {
    icon.classList.remove("fa fa-eye");
    icon.classList.add("fa fa-eye-slash");
  } else {
    icon.classList.add("fa fa-eye");
    icon.classList.remove("fa fa-eye-slash");
  }
};
