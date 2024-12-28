const Helpers = {
    diffPorcentagem: (total: number, value: number) => {
        const v = (value / total) * 100;
        return v.toFixed(2);
    },
    parsePorcentagem: (total: string, value: string) => {
        const totalM = parseFloat(total);
        const valueM = parseFloat(value);
        const result = ((totalM / valueM) * 100) - 100;
        if(value && total) {
            return result.toFixed(2);
        }
        return 0;
    },
    converterFloatReal: (value: string) => {
        let inteiro = null, decimal = null, c = null, j = null;
        let aux = new Array();
        try {
          value = "" + value.toFixed(2);
        } catch(e) {}
        
        if(!value) {
          return 0;
        }
        c = value.indexOf(".", 0);
        //encontrou o ponto na string
        if(c > 0){
           //separa as partes em inteiro e decimal
           inteiro = value.substring(0, c);
           decimal = value.substring(c + 1, value.length);
        } else{
           inteiro = value;
        }
       
        //pega a parte inteiro de 3 em 3 partes
        for (j = inteiro.length, c = 0; j > 0; j-=3, c++){
            aux[c]=inteiro.substring(j-3,j);
        }
       
        //percorre a string acrescentando os pontos
        inteiro = "";
        for(c = aux.length-1; c >= 0; c--){
           inteiro += aux[c]+'.';
        }
        //retirando o ultimo ponto e finalizando a parte inteiro
       
        inteiro = inteiro.substring(0,inteiro.length-1);
       
        decimal = parseInt(decimal);
        if(isNaN(decimal)){
           decimal = "00";
        } else{
           decimal = "" + decimal;
           if(decimal.length === 1){
              decimal = "0" + decimal;
           }
        }
        value = inteiro + "," + decimal;
        return 'R$ ' + value;
    },
    today: () => {
        const date = new Date();
        let month = date.getMonth() + 1;
        month = month < 10 ? `0${month}` : month;
        return `${date.getDate()}/${month}/${date.getFullYear()}`
    },
    currency: (value: string) => {
        if(value) {
            value = value.replace(/\D/g, "");
            value = value.replace(/(\d)(\d{2})$/, "$1,$2");
            value = value.replace(/(?=(\d{3})+(\D))\B/g, ".");
            // e.currentTarget.value = value;
        }
        
        return value;
    }
};

export default Helpers;