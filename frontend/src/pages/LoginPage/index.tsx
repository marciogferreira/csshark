import { useContext } from "react";
import { Navigate, useNavigate } from "react-router-dom";
import AuthContext from "../../contexts/AuthContext";
import { ErrorMessage, Field, Formik } from "formik";
import Api from "../../core/api";
import Message from "../../core/Message";
import logo from '../../assets/logo.png';
import * as Yup from 'yup';
const LoginPage = () => {
  
  const navigate = useNavigate();
  const { isLogged, signIn } = useContext(AuthContext)

  async function login(values: any) {
    try {
      const response = await Api.post('login', {
        email: values.login,
        password: values.senha
      });
      Message.success(response.data.message)
      signIn(response.data.token);
      navigate('/');
    } catch(e) {
    } finally {
    }
  }

  

  if(isLogged) {
    return <Navigate to="/" />
  }
  
  return (
    <div >
    <div id="login-page" className="d-flex justify-content-center align-items-center vh-100">
      <div className="card p-4 shadow" style={{ width: "350px" }}>
        <div className="text-center mb-4">
          <div className="d-flex justify-content-center">
            <img src={logo} width="100px" alt="" />
          </div>
          <h5>Bem-vindo(a) à Academia</h5>
          
        </div>
        <Formik
          initialValues={{
            login: '',
            senha: ''
          }}
          validationSchema={Yup.object().shape({
            login: Yup.string()
              .required('Campo obrigatório.'),
            senha: Yup.string()
              .min(6, 'A senha deve ter pelo menos 6 caracteres.')
              .required('A senha é obrigatória.'),
          })}
          onSubmit={(values) => {
            login(values)
          }}
        >
          {({ handleSubmit }) => (
            <>
              <div className="mb-3">
                <label htmlFor="login" className="form-label">
                  Login
                </label>
                <Field 
                  type="text"
                  name="login"
                  id="login"
                  placeholder="Digite seu email ou CPF"
                  className="form-control"
                />
                <ErrorMessage name="login" className="error" />
              </div>
              <div className="mb-3">
                <label htmlFor="senha" className="form-label">
                  Senha
                </label>
                <Field 
                  type="password"
                  id="senha"
                  name="senha"
                  className="form-control"
                  placeholder="Digite sua Senha"
                />
                <ErrorMessage name="senha" className="error" />
              </div>
              <div className="mb-3 text-end">
                <a href="/recuperar-senha" className="text-decoration-none">
                  Esqueceu sua senha?
                </a>
              </div>
              <button onClick={() => handleSubmit()} type="button" className="btn btn-primary w-100">
                Entrar
              </button>
            </>
          )}
        </Formik>
        
        <div className="text-center mt-3">
          <button className="btn btn-outline-secondary w-100" onClick={() => navigate('/aluno/novo')}>
            Novo Aluno
          </button>
        </div>
      </div>
    </div>
    </div>
  );
};

export default LoginPage;
