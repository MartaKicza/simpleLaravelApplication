import React, {memo} from 'react';
import './Alert.css';
import classNames from 'classnames';

const Alert = ({children, ...props}) =>
  <div className={classNames(props.className, 'Alert')}>{children}</div>;

export default memo(Alert);
