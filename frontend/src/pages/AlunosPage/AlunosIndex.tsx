import ReactInputMask from 'react-input-mask';
import Crud from '../../components/Crud';
import { ReactElement } from 'react';
import { Form } from 'react-bootstrap';
import Api from '../../core/api';

type DataProps = {
  Field: ReactElement | any;
  ErrorMessage: ReactElement | any;
  values: any;
  setFieldValue: (name: string, value: any) => void
}
const FormWrapper = ({ Field, ErrorMessage, values, setFieldValue }: DataProps) => {
   
    return (
        <>
            <div className='row'>
                <div className="col-md-6">
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
                        <label>CPF</label>
                        <Field name="cpf" type="text" className="form-control" />
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

                    <div className='mb-3'>
                        <label>Peso (kg)</label>
                        <Field name="peso" type="number" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="peso"  className="error" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Altura (m)</label>
                        <Field name="altura" type="number" step="0.01" className="form-control" />
                        <span className="error" >
                            <ErrorMessage name="altura"  className="error" />
                        </span>
                    </div>

                    <div className='mb-3'>
                        <label>Telefone</label>
                          <ReactInputMask 
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

                    <div className='mb-3'>
                        <label>Direito</label>
                        <Field name="direito" type="text"className="form-control"  />
                        <span className="error">
                            <ErrorMessage name="direito"  className="error" />
                        </span>
                    </div>
                </div>
                <div className="col-md-6">
                
                    <div className='mb-3'>
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
                    </div>

                    <div className='mb-3'>
                        <label>Lesão</label>
                        <Field name="lesao" type="text" className="form-control" />
                        <span className="error">
                        <ErrorMessage name="lesao"  className="error" />
                        </span>
                        
                    </div>

                    <div className='mb-3'>
                        <label>Medicamentos</label>
                        <Field name="medicamentos" type="text" className="form-control" />
                        <span className="error">
                        <ErrorMessage name="medicamentos"  className="error" />
                        </span>
                        
                    </div>

                    <div className='mb-3'>
                        <label>Estado Ativo</label>
                        <Field name="estadoAtivo" type="text" className="form-control" />
                        <span className="error">
                        <ErrorMessage name="estadoAtivo"  className="error" />
                        </span>
                        
                    </div>

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
        </>
    );
}

export default function AlunosIndex() {
    return (
        <Crud
            title="Alunos"
            endPoint="alunos"
            searchFieldName='search'
            placeholderSearch="Pesquise por Nome ou CPF"
            emptyObject={{
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
            FormSearch={({ params, setParams }: any) =>(
                <div className='row'>
                    <div className="col-md-3">
                        <label htmlFor="">Status</label>
                        <select onChange={e => setParams({...params, ...{ status: e.target.value }})} className='form-control'>
                            <option value="">Selecione</option>
                            <option value="true">Ativos</option>
                            <option value="false">Inativos</option>
                        </select>
                    </div>
                </div>
            )}
            fields={[
                { name: 'id', label: 'Id', classBody: 'min-width' },
                { name: 'nome', label: 'Nome' },
                { name: 'cpf', label: 'CPF' },
                { name: 'email', label: 'E-mail' },
                { name: 'esquerdo', label: 'Telefone' },
                { name: 'esquerdo', label: 'Ações' },
            ]}
            fieldsHtml={({ item, setList } : any) => {
                async function updateStatus(id: number, status: boolean) {
                    await Api.put(`alunos/${id}`, {
                        status
                    });
                    setList((prevList: any) => {
                        return prevList.map((item: any) => {
                            if(item.id === id) {
                                item.status = status;
                            }
                            return item;
                        });
                    })
                }

                return (
                    <>
                        <td>{item.id}</td>
                        <td>{item.nome}</td>
                        <td>{item.cpf}</td>
                        <td>{item.email}</td>
                        <td>{item.esquerdo}</td>
                        <td>
                        <Form.Check // prettier-ignore
                            type="switch"
                            id="custom-switch"
                            label="Status"
                            checked={item.status}
                            onChange={e => updateStatus(item.id, e.target.checked)}
                        />
                        </td>
                    </>
                )
            }}
            validation={(Yup: object | any) => {
                return {
                   nome: Yup.string().required('O nome é obrigatório'),
                   email: Yup.string().required('O nome é obrigatório'),
                   cpf: Yup.string().required('O nome é obrigatório'),
                    dataInicio: Yup.date().required('A data de início é obrigatória'),
                    professor: Yup.string().required('O professor é obrigatório'),
                    peso: Yup.number()
                        .positive('O peso deve ser positivo')
                        .required('O peso é obrigatório'),
                    altura: Yup.number()
                        .positive('A altura deve ser positiva')
                        .required('A altura é obrigatória'),
                    esquerdo: Yup.string().required('Este campo é obrigatório'),
                    direito: Yup.string().required('Este campo é obrigatório'),
                    hipertensao: Yup.boolean(),
                    diabetes: Yup.boolean(),
                    fibromialgia: Yup.boolean(),
                    artrite: Yup.boolean(),
                    lesao: Yup.string(),
                    medicamentos: Yup.string(),
                    estadoAtivo: Yup.string().required('O estado ativo é obrigatório'),
                    modalidade: Yup.string().required('A modalidade é obrigatória'),
                    frequenciaSemanal: Yup.string().required('A frequência semanal é obrigatória'),
                    objetivo: Yup.string().required('O objetivo é obrigatório'),
                };
            } }
            FormWrapper={FormWrapper} 
            columns={[]} 
            enableBtnNew={false} 
            saveContinueForm={false} 
            showActionsColumn={false} 
            showDeleteColumn={false} 
            showEditColumn={false} 
            showNewButton={false} 
            showSearch={false} 
            showPagination={false} 
            showSort={false}
            showTotal={false} 
            showViewHistory={false}
            showViewAlterStatus={false}        />
    );
}
