import swal from 'sweetalert';

const Message = {
    success: (msg: string) => {
        swal("Sucesso", msg, "success");
    },
    error: (msg: string) => {
        swal("Oops!", msg, "error");
    },
    infor: (msg: string) => {
        swal("Oops!", msg, "info");
    },
    confirmation: (msg: string, callback: () => void) => {
        swal({
          title: msg,
          text: "Confirme abaixo",
          icon: "warning",
          buttons: {
            cancel: {
              text: "Cancel",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "OK",
              value: true,
              visible: true,
              className: "",
              closeModal: true
            }
          },
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            callback();
          }
        });
    },
    validation: async (error: any) => {
        //const errors = error.response.data.errors;
        const errors = error?.response.data.validation;
        //console.log(errors);
        // VALIDATION SYMFONY
        // errors.map(item => {
        //     Message.error(item);
        // });
        // VALIDATION LARAVEL
        
        Object.keys(errors).map(name => {
            if(errors[name].length) {
                Message.error(errors[name][0]);
            }
        });
    }
}

export default Message;