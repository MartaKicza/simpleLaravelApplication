import {useState, useEffect, useReducer} from 'react';
import axios from 'axios';

const dataFetchingReducer = (state, action) => {
  switch (action.type) {
    case "FETCH_INIT":
      return {
        ...state,
        isLoading: true,
        isError: false,
      };
    case "FETCH_SUCCESS":
      return {
        ...state,
        isLoading: false,
        isError: false,
        data: action.payload
      };
    case "FETCH_ERROR":
      return {
        ...state,
        isLoading: false,
        isError: true,
        data: action.error,
      };
    default:
      throw new Error('Action type not know in dataFetchingReducer.');
  }
}

const useDataApi = (initialUrl, initialData, initialParams, type) => {
  const [url, setUrl] = useState(initialUrl);
  const [params, setParams] = useState(initialParams);
  const [state, dispatch] = useReducer(dataFetchingReducer, {
    isLoading: false,
    isError: false,
    data: initialData
  });

  useEffect(() => {
    let didCancel = false;

    const fetchData = () => {
      dispatch({ type: 'FETCH_INIT' });
      try {
        if (!didCancel) {
          console.log({method: type, url: url, data: params});
          axios({method: type, url: url, data: params})
            .then(function (response) {
              dispatch({ type: 'FETCH_SUCCESS', payload: response.data });
            })
            .catch(function (error) {
              if (!didCancel) {
                dispatch({ type: 'FETCH_ERROR', error: error });
              }
            });
        }
      } catch (error) {
        dispatch({ type: 'FETCH_ERROR', error: error });
      }
    }
    if ((type !== 'post' && type !== 'put') || params !== null) { 
      fetchData();
    }

    return () => {
      didCancel = true;
    };

  }, [url, params, type]);

  return [state, setUrl, setParams];
}

export default useDataApi
