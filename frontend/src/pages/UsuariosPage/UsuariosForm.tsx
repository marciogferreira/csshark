import { Formik, Form, Field, ErrorMessage } from "formik";
import * as Yup from "yup";
import "bootstrap/dist/css/bootstrap.min.css";
import { useNavigate } from "react-router-dom";

// Esquema de validação com Yup
const validationSchema = Yup.object({
  nome: Yup.string().max(100, "Máximo de 100 caracteres").required("Nome é obrigatório"),
  email: Yup.string().email("Email inválido").max(100, "Máximo de 100 caracteres").required("Email é obrigatório"),
  senha: Yup.string().min(6, "A senha deve ter no mínimo 6 caracteres").max(255, "Máximo de 255 caracteres").required("Senha é obrigatória"),
  tipo_usuario: Yup.string()
    .oneOf(["admin", "instrutor", "atendente"], "Tipo de usuário inválido")
    .required("Tipo de usuário é obrigatório"),
  data_criacao: Yup.date().required("Data de criação é obrigatória"),
});

const UsuariosForm = () => {
  const navigate = useNavigate();
  return (
    <div className="container mt-5">
      <h2 className="mb-4">Cadastro de Usuário</h2>
      <Formik
        initialValues={{
          nome: "",
          email: "",
          senha: "",
          tipo_usuario: "admin",
          data_criacao: new Date().toISOString().slice(0, 19), // Formato ISO para inicialização
        }}
        validationSchema={validationSchema}
        onSubmit={(values) => {
          console.log("Dados enviados:", values);
          alert("Formulário enviado com sucesso!");
        }}
      >
        {({ isSubmitting }) => (
          <Form>
            {/* Nome */}
            <div className="mb-3">
              <label htmlFor="nome" className="form-label">
                Nome
              </label>
              <Field
                type="text"
                id="nome"
                name="nome"
                className="form-control"
                placeholder="Digite o nome completo"
              />
              <ErrorMessage name="nome" component="div" className="text-danger" />
            </div>

            {/* Email */}
            <div className="mb-3">
              <label htmlFor="email" className="form-label">
                Email
              </label>
              <Field
                type="email"
                id="email"
                name="email"
                className="form-control"
                placeholder="Digite o email"
              />
              <ErrorMessage name="email" component="div" className="text-danger" />
            </div>

            {/* Senha */}
            <div className="mb-3">
              <label htmlFor="senha" className="form-label">
                Senha
              </label>
              <Field
                type="password"
                id="senha"
                name="senha"
                className="form-control"
                placeholder="Digite a senha"
              />
              <ErrorMessage name="senha" component="div" className="text-danger" />
            </div>

            {/* Tipo de Usuário */}
            <div className="mb-3">
              <label htmlFor="tipo_usuario" className="form-label">
                Tipo de Usuário
              </label>
              <Field
                as="select"
                id="tipo_usuario"
                name="tipo_usuario"
                className="form-select"
              >
                <option value="admin">Admin</option>
                <option value="instrutor">Instrutor</option>
                <option value="atendente">Atendente</option>
              </Field>
              <ErrorMessage name="tipo_usuario" component="div" className="text-danger" />
            </div>

            {/* Botão de Enviar */}
            <button
              type="submit"
              className="btn btn-primary"
              disabled={isSubmitting}
            >
              {isSubmitting ? "Salvando..." : "Salvar"}
            </button>
            &nbsp;
            <button
              onClick={() => navigate('/usuarios')}
              type="reset"
              className="btn btn-warning"
            >
              Cancelar
            </button>
          </Form>
        )}
      </Formik>
    </div>
  );
};

export default UsuariosForm;
