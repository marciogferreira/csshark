import { ErrorMessage, Field, Formik } from "formik";
import * as Yup from 'yup'
import Api from "../../core/api";
import Message from "../../core/Message";
import { useNavigate } from "react-router-dom";
import { useContext, useState } from "react";
import AuthContext from "../../contexts/AuthContext";
import InputMask from 'react-input-mask';
function NovoAlunoPage() {

    const navigate = useNavigate();
    const[enviando, setEnviando] = useState(false)
    const { setLoading } = useContext(AuthContext)
    const schemaValidator = Yup.object().shape({
        nome: Yup.string().required('O nome é obrigatório'),
        email: Yup.string().required('O E-mail é obrigatório'),
        cpf: Yup.string().required('O CPF é obrigatório').min(11, 'CPF Inválido.'),
        dataInicio: Yup.date().required('A data de início é obrigatória'),
        professor: Yup.string().required('O professor é obrigatório'),
        // peso: Yup.number()
        //     .positive('O peso deve ser positivo')
        //     .required('O peso é obrigatório'),
        altura: Yup.number()
            .positive('A altura deve ser positiva')
            .required('A altura é obrigatória'),
        esquerdo: Yup.string().required('Este campo é obrigatório'),
        // direito: Yup.string().required('Este campo é obrigatório'),
        hipertensao: Yup.boolean(),
        diabetes: Yup.boolean(),
        fibromialgia: Yup.boolean(),
        artrite: Yup.boolean(),
        lesao: Yup.string(),
        medicamentos: Yup.string(),
        // estado: Yup.string().required('O estado ativo é obrigatório'),
        modalidade: Yup.string().required('A modalidade é obrigatória'),
        // frequenciaSemanal: Yup.string().required('A frequência semanal é obrigatória'),
        // objetivo: Yup.string().required('O objetivo é obrigatório'),
    })

    async function saveForm(values: any, form: any) {
        try {
            setLoading(true)
            setEnviando(true)
            await Api.post('aluno/novo', values)
            Message.success("Aluno(a) Cadastrado(a) com Sucesso. \n Seja bem-vindo(a) ao Nosso Time! \n Agora dirija-se a recepção do Box para finalizar sua matrícula.")
            form.resetForm();
            navigate('/login');
        } catch (error: any) {
            console.error(error)
        } finally {
            setLoading(false)
            setEnviando(false)
        }
    }

    return (
        <div className="page-aluno-novo">
            <header id="header-aluno-novo">
                <br />
                <h2 className="text-center mb-3">Novo Cadastro</h2>
                <p>Seja um(a) aluno(a) Shark!</p>
            </header>
        
            <div className="container mt-5">
                <Formik
                    enableReinitialize
                    initialValues={{
                        nome: '',
                        email: '',
                        cpf: '',
                        dataInicio: '',
                        professor: '',
                        peso: '',
                        altura: '',
                        esquerdo: '',
                        direito: '',
                        hipertensao: false,
                        diabetes: false,
                        fibromialgia: false,
                        artrite: false,
                        lesao: '',
                        medicamentos: '',
                        estadoAtivo: '',
                        modalidade: '',
                        frequenciaSemanal: '',
                        objetivo: '',
                    }}
                    validationSchema={schemaValidator}
                    onSubmit={(values: any, form: any) => {
                        saveForm(values, form)
                    }}
                >
                  {({ handleSubmit, values, setFieldValue }) => (
                    <div className="card p-3">
                        <div className='row'>
                            <div className="col-12 col-md-6">
                                <div className='mb-3'>
                                    <label>Nome</label>
                                    <Field name="nome" type="text" className="form-control" />
                                    <span className="error" >
                                        <ErrorMessage name="nome" component="span" />
                                    </span>
                                </div>
                                <div className='mb-3'>
                                    <label>E-mail</label>
                                    <Field name="email" type="text" className="form-control" />
                                    <span className="error" >
                                        <ErrorMessage name="email" component="span" />
                                    </span>
                                </div>
                                <div className='mb-3'>
                                    {values.cpf}
                                    <label>CPF</label>
                                    <InputMask 
                                        name="cpf" 
                                        type="text" 
                                        className="form-control" 
                                        mask="999.999.999-99"
                                        value={values.cpf}
                                        onChange={(e: any) => {
                                            const value = e.target.value;
                                            let cpf = value.replaceAll('.', '');
                                            cpf = cpf.replaceAll('-', '');
                                            cpf = cpf.replaceAll('_', '');
                                            setFieldValue('cpf', cpf)
                                        }}
                                    />
                                    <span className="error" >
                                        <ErrorMessage name="cpf" component="span" />
                                    </span>
                                </div>

                                <div className='mb-3'>
                                    <label>Data de Início</label>
                                    <Field name="dataInicio" type="date" className="form-control" />
                                    <span className="error" >
                                        <ErrorMessage name="dataInicio"  className="error" />
                                    </span>
                                </div>

                                <div className='mb-3'>
                                    <label>Professor</label>
                                    <Field name="professor" type="text" className="form-control" />
                                    <span className="error" >
                                        <ErrorMessage name="professor"  className="error" />
                                    </span>
                                </div>

                                {/* <div className='mb-3'>
                                    <label>Peso (kg)</label>
                                    <Field name="peso" type="number" className="form-control" />
                                    <span className="error" >
                                        <ErrorMessage name="peso"  className="error" />
                                    </span>
                                </div> */}

                                <div className='mb-3'>
                                    <label>Altura (m)</label>
                                    <Field name="altura" type="number" step="0.01" className="form-control" />
                                    <span className="error" >
                                        <ErrorMessage name="altura"  className="error" />
                                    </span>
                                </div>

                                <div className='mb-3'>
                                    <label>Telefone</label>
                                    <InputMask 
                                        name="esquerdo" 
                                        type="text" 
                                        className="form-control" 
                                        mask="(99) 99999.9999"
                                        value={values.esquerdo}
                                        onChange={(e: any) => {
                                            const value = e.target.value;
                                            setFieldValue('esquerdo', value)
                                        }}
                                    />
                                    <span className="error" >
                                        <ErrorMessage name="esquerdo"  className="error" />
                                    </span>
                                </div>

                                {/* <div className='mb-3'>
                                    <label>Direito</label>
                                    <Field name="direito" type="text"className="form-control"  />
                                    <span className="error">
                                        <ErrorMessage name="direito"  className="error" />
                                    </span>
                                </div> */}
                            </div>
                            <div className="col-12 col-md-6">
                            
                                {/* <div className='mb-3'>
                                    <label>
                                    <Field name="hipertensao" type="checkbox" />
                                    &nbsp;
                                    Hipertensão
                                    </label>
                                </div>

                                <div className='mb-3'>
                                    <label>
                                    <Field name="diabetes" type="checkbox" />&nbsp;
                                    Diabetes
                                    </label>
                                </div>

                                <div className='mb-3'>
                                    <label>
                                    <Field name="fibromialgia" type="checkbox" />&nbsp;
                                    Fibromialgia
                                    </label>
                                </div>

                                <div className='mb-3'>
                                    <label>
                                    <Field name="artrite" type="checkbox" />&nbsp;
                                    Artrite
                                    </label>
                                </div> */}

                                <div className='mb-3'>
                                    <label>Lesão</label>
                                    <Field name="lesao" type="text" className="form-control" />
                                    <span className="error">
                                    <ErrorMessage name="lesao"  className="error" />
                                    </span>
                                    
                                </div>

                                <div className='mb-3'>
                                    <label>Doenças</label>
                                    <Field name="medicamentos" type="text" className="form-control" />
                                    <span className="error">
                                    <ErrorMessage name="medicamentos"  className="error" />
                                    </span>
                                    
                                </div>

                                {/* <div className='mb-3'>
                                    <label>Estado Ativo</label>
                                    <Field name="estadoAtivo" type="text" className="form-control" />
                                    <span className="error">
                                    <ErrorMessage name="estadoAtivo"  className="error" />
                                    </span>
                                    
                                </div> */}

                                <div className='mb-3'>
                                    <label>Modalidade</label>
                                    <Field name="modalidade" type="text" className="form-control" />
                                    <span className="error">
                                    <ErrorMessage name="modalidade"  className="error" />
                                    </span>
                                    
                                </div>

                                <div className='mb-3'>
                                    <label>Frequência Semanal</label>
                                    <Field name="frequenciaSemanal" type="text"className="form-control"  />
                                    <span className="error">
                                    <ErrorMessage name="frequenciaSemanal"  className="error" />
                                    </span>
                                    
                                </div>

                                <div className='mb-3'>
                                    <label>Objetivo</label>
                                    <Field name="objetivo" type="text" className="form-control" />
                                    <span className="error">
                                    <ErrorMessage name="objetivo"  className="error" />
                                    </span>
                                    
                                </div>


                            </div>
                        </div>      

                        <div className="d-flex justify-content-center flex-column mt-3 mb-5">
                            <button className="btn btn-primary" disabled={enviando} onClick={() => handleSubmit()}>Finalizar Cadastro</button>     
                            <button className="btn btn-warning mt-3" onClick={() => navigate('/login')}>Cancelar</button>     
                        </div>
                    </div>
                  )}
                </Formik>
            </div>
        </div>
    )
}

export default NovoAlunoPage;