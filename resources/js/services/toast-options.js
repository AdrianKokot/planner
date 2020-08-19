
function toastOptions(type = "success", title = 'default', variant = 'default', delay = 5000, toaster = 'b-toaster-bottom-center') {
  title = title != 'default' ? title : (type == 'success' ? 'Success' : 'Error');
  variant = variant != 'default' ? variant : (type == 'success' ? 'success' : 'danger');
  return {
    title: title,
    variant: variant,
    autoHideDelay: delay,
    solid: true,
    toaster: toaster,
  }
}

export default toastOptions;
