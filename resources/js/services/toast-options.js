
export function toastOptions(type = "success", title = 'default', variant = 'default', delay = 3000, toaster = 'b-toaster-top-center') {
  title = title != 'default' ? title : (type == 'success' ? 'Success' : 'Error');
  variant = variant != 'default' ? variant : (type == 'success' ? 'primary' : 'danger');
  return {
    title: title,
    variant: variant,
    autoHideDelay: delay,
    solid: true,
    toaster: toaster,
  }
}

export function msgBoxOptions(title = "Confirmation", size = "md", okTitle = "Yes", cancelTitle = "Cancel") {
  return {
    title,
    titleTag: 'h5',
    size,
    okVariant: 'outline-danger',
    okTitle,
    cancelVariant: 'outline-secondary',
    cancelTitle,
    hideHeaderClose: true,
  }
}
