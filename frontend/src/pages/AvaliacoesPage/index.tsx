import { useContext, useEffect, useState } from 'react';
import { Formik, Form, Field, ErrorMessage } from 'formik';
import * as Yup from 'yup';
import Api from '../../core/api';
import Message from '../../core/Message';
import AuthContext from '../../contexts/AuthContext';

const AvaliacoesPage = () => {
  const [avaliacoes, setAvaliacoes] = useState([]);
  const { user } = useContext(AuthContext);

  // Consulta todas as avaliações
  const fetchAvaliacoes = async () => {
    try {
      const response = await Api.get('avaliacoes', {
        params: {
            cpf: user.email
        }
      });
      const data = await response.data.data;
      setAvaliacoes(data);
    } catch (error) {
      console.error('Erro ao buscar avaliações:', error);
    }
  };

  useEffect(() => {
    fetchAvaliacoes();
  }, []);

  // Validação com Yup
  const validationSchema = Yup.object().shape({
    data: Yup.date().required('Obrigatório'),
    peso: Yup.string(),
    altura: Yup.string(),
    torax: Yup.string(),
    abdomen: Yup.string(),
    cintura: Yup.string(),
    quadril: Yup.string(),
    braco_direito: Yup.string(),
    braco_esquerdo: Yup.string(),
    ant_braco_direito: Yup.string(),
    ant_braco_esquerdo: Yup.string(),
    coxa_direito: Yup.string(),
    coxa_esquerdo: Yup.string(),
    panturrilha_direito: Yup.string(),
    panturrilha_esquerdo: Yup.string(),
  });

  // Envia os dados
  const handleSubmit = async (values: any, { resetForm }: any) => {
    try {
      await Api.post('avaliacoes', values);
      Message.success("Avaliação Realizada com Sucesso!");
      resetForm();
      fetchAvaliacoes();
    } catch (error) {
      Message.error('Erro ao enviar avaliação:' + error);
    } finally {
        
    }
  };

  return (
    <div className="p-6 max-w-4xl mx-auto">
      <h2 className="text-2xl font-bold mb-4">Nova Avaliação Física</h2>

      <Formik
        initialValues={{
          aluno_id: '',
          data: '',
          peso: '',
          altura: '',
          torax: '',
          abdomen: '',
          cintura: '',
          quadril: '',
          braco_direito: '',
          braco_esquerdo: '',
          ant_braco_direito: '',
          ant_braco_esquerdo: '',
          coxa_direito: '',
          coxa_esquerdo: '',
          panturrilha_direito: '',
          panturrilha_esquerdo: '',
        }}
        validationSchema={validationSchema}
        onSubmit={handleSubmit}
      >
        <Form className="grid grid-cols-2 gap-4">
          {[
            'data',
            'peso',
            'altura',
            'torax',
            'abdomen',
            'cintura',
            'quadril',
            'braco_direito',
            'braco_esquerdo',
            'ant_braco_direito',
            'ant_braco_esquerdo',
            'coxa_direito',
            'coxa_esquerdo',
            'panturrilha_direito',
            'panturrilha_esquerdo',
          ].map((field) => (
            <div key={field}>
              <label className="block font-semibold capitalize" htmlFor={field}>
                {field.replace(/_/g, ' ')}
              </label>
              <Field
                id={field}
                name={field}
                type={field === 'data' ? 'date' : 'text'}
                className="w-full p-2 border border-gray-300 rounded"
              />
              <ErrorMessage name={field} component="div" className="text-red-500 text-sm" />
            </div>
          ))}

          <div className="col-span-2 mt-4">
            <button
              type="submit"
              className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-900"
            >
              Salvar Avaliação
            </button>
          </div>
        </Form>
      </Formik>

      <h3 className="text-xl font-bold mt-8 mb-4">Avaliações Realizadas</h3>
      {avaliacoes.length === 0 ? (
        <p>Nenhuma avaliação cadastrada.</p>
      ) : (
        <div className="row">
            {avaliacoes.map((item, index) => (
                <div key={index} className="col-md-6 col-lg-4 mb-4">
                    <div className="card shadow-sm">
                        <div className="card-body" style={{ background: '#F7F7F7' }}>
                            <h5 className="card-title mb-3">
                                <strong>{index + 1}ª Avaliação</strong>
                                <hr />
                            </h5>
                            <div className="row">
                                {[
                                'peso',
                                'altura',
                                'torax',
                                'abdomen',
                                'cintura',
                                'quadril',
                                'braco_direito',
                                'braco_esquerdo',
                                'ant_braco_direito',
                                'ant_braco_esquerdo',
                                'coxa_direito',
                                'coxa_esquerdo',
                                'panturrilha_direito',
                                'panturrilha_esquerdo',
                                ].map((field) => (
                                <div className="col-6 mb-2" key={field}>
                                    {field.toUpperCase().replace(/_/g, ' ')}: <strong>{item[field] || 'N/A'}</strong>
                                </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            ))}
        </div>
      )}
    </div>
  );
};

export default AvaliacoesPage;
