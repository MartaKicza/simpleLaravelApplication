import React, {memo} from 'react';
import PropTypes from 'prop-types';
import "./Button.css";
import classNames from 'classnames';

const Button = function Button (props) {
  return props.href 
    ? <a href={props.href} className={classNames("Button", props.className)} onClick={props.clickAction}>{props.children}</a> 
    : <button type={props.type ? props.type : "button"} className={classNames("Button", props.className)} onClick={props.clickAction}>{props.children}</button>
};

Button.propTypes = {
  href: PropTypes.string,
  clickAction: PropTypes.func,
}

Button.defaultProps = {
  clickAction: () => {},
}

export default memo(Button)
