	var __webviewId__ = __webviewId__; 	var __wxAppCode__= __wxAppCode__ || {}; 	var __swan_GLOBAL__= __swan_GLOBAL__ || {entrys:{},defines:{},modules:{},ops:[],wxs_nf_init:undefined,total_ops:0}; 	var __subPageFrameStartTime__ = Date.now(); 	 
	/*v0.5vv_20180814_syb_cb_crawl*/window.__wcc_version__='v0.5vv_20180814_syb_cb_crawl';window.__wcc_version_info__={"customComponents":true,"fixZeroRpx":true,"propValueDeepCopy":false};
var $gwxc
var $gaic={}
$gwx3=function(path,global){
if(typeof global === 'undefined') global={};if(typeof __swan_GLOBAL__ === 'undefined') {__swan_GLOBAL__={};
}$gwx('init', global);
function _(a,b){if(typeof(b)!='undefined')a.children.push(b);}
function _v(k){if(typeof(k)!='undefined')return {tag:'virtual','wxKey':k,children:[]};return {tag:'virtual',children:[]};}
function _n(tag){$gwxc++;if($gwxc>=16000){throw 'Dom limit exceeded, please check if there\'s any mistake you\'ve made.'};return {tag:'wx-'+tag,attr:{},children:[],n:[],raw:{},generics:{}}}
function _p(a,b){b&&a.properities.push(b);}
function _s(scope,env,key){return typeof(scope[key])!='undefined'?scope[key]:env[key]}
function _wp(m){console.warn("swanRT_$gwx3:"+m)}
function _wl(tname,prefix){_wp(prefix+':-1:-1:-1: Template `' + tname + '` is being called recursively, will be stop.')}
$gwn=console.warn;
$gwl=console.log;
function $gwh()
{
function x()
{
}
x.prototype = 
{
hn: function( obj, all )
{
if( typeof(obj) == 'object' )
{
var cnt=0;
var any1=false,any2=false;
for(var x in obj)
{
any1=any1|x==='__value__';
any2=any2|x==='__wxspec__';
cnt++;
if(cnt>2)break;
}
return cnt == 2 && any1 && any2 && ( all || obj.__wxspec__ !== 'm' || this.hn(obj.__value__) === 'h' ) ? "h" : "n";
}
return "n";
},
nh: function( obj, special )
{
return { __value__: obj, __wxspec__: special ? special : true }
},
rv: function( obj )
{
return this.hn(obj,true)==='n'?obj:this.rv(obj.__value__);
},
hm: function( obj )
{
if( typeof(obj) == 'object' )
{
var cnt=0;
var any1=false,any2=false;
for(var x in obj)
{
any1=any1|x==='__value__';
any2=any2|x==='__wxspec__';
cnt++;
if(cnt>2)break;
}
return cnt == 2 && any1 && any2 && (obj.__wxspec__ === 'm' || this.hm(obj.__value__) );
}
return false;
}
}
return new x;
}
wh=$gwh();
function $gstack(s){
var tmp=s.split('\n '+' '+' '+' ');
for(var i=0;i<tmp.length;++i){
if(0==i) continue;
if(")"===tmp[i][tmp[i].length-1])
tmp[i]=tmp[i].replace(/\s\(.*\)$/,"");
else
tmp[i]="at anonymous function";
}
return tmp.join('\n '+' '+' '+' ');
}
function $gwrt( should_pass_type_info )
{
function ArithmeticEv( ops, e, s, g, o )
{
var _f = false;
var rop = ops[0][1];
var _a,_b,_c,_d, _aa, _bb;
switch( rop )
{
case '?:':
_a = rev( ops[1], e, s, g, o, _f );
_c = should_pass_type_info && ( wh.hn(_a) === 'h' );
_d = wh.rv( _a ) ? rev( ops[2], e, s, g, o, _f ) : rev( ops[3], e, s, g, o, _f );
_d = _c && wh.hn( _d ) === 'n' ? wh.nh( _d, 'c' ) : _d;
return _d;
break;
case '&&':
_a = rev( ops[1], e, s, g, o, _f );
_c = should_pass_type_info && ( wh.hn(_a) === 'h' );
_d = wh.rv( _a ) ? rev( ops[2], e, s, g, o, _f ) : wh.rv( _a );
_d = _c && wh.hn( _d ) === 'n' ? wh.nh( _d, 'c' ) : _d;
return _d;
break;
case '||':
_a = rev( ops[1], e, s, g, o, _f );
_c = should_pass_type_info && ( wh.hn(_a) === 'h' );
_d = wh.rv( _a ) ? wh.rv(_a) : rev( ops[2], e, s, g, o, _f );
_d = _c && wh.hn( _d ) === 'n' ? wh.nh( _d, 'c' ) : _d;
return _d;
break;
case '+':
case '*':
case '/':
case '%':
case '|':
case '^':
case '&':
case '===':
case '==':
case '!=':
case '!==':
case '>=':
case '<=':
case '>':
case '<':
case '<<':
case '>>':
_a = rev( ops[1], e, s, g, o, _f );
_b = rev( ops[2], e, s, g, o, _f );
_c = should_pass_type_info && (wh.hn( _a ) === 'h' || wh.hn( _b ) === 'h');
switch( rop )
{
case '+':
_d = wh.rv( _a ) + wh.rv( _b );
break;
case '*':
_d = wh.rv( _a ) * wh.rv( _b );
break;
case '/':
_d = wh.rv( _a ) / wh.rv( _b );
break;
case '%':
_d = wh.rv( _a ) % wh.rv( _b );
break;
case '|':
_d = wh.rv( _a ) | wh.rv( _b );
break;
case '^':
_d = wh.rv( _a ) ^ wh.rv( _b );
break;
case '&':
_d = wh.rv( _a ) & wh.rv( _b );
break;
case '===':
_d = wh.rv( _a ) === wh.rv( _b );
break;
case '==':
_d = wh.rv( _a ) == wh.rv( _b );
break;
case '!=':
_d = wh.rv( _a ) != wh.rv( _b );
break;
case '!==':
_d = wh.rv( _a ) !== wh.rv( _b );
break;
case '>=':
_d = wh.rv( _a ) >= wh.rv( _b );
break;
case '<=':
_d = wh.rv( _a ) <= wh.rv( _b );
break;
case '>':
_d = wh.rv( _a ) > wh.rv( _b );
break;
case '<':
_d = wh.rv( _a ) < wh.rv( _b );
break;
case '<<':
_d = wh.rv( _a ) << wh.rv( _b );
break;
case '>>':
_d = wh.rv( _a ) >> wh.rv( _b );
break;
default:
break;
}
return _c ? wh.nh( _d, "c" ) : _d;
break;
case '-':
_a = ops.length === 3 ? rev( ops[1], e, s, g, o, _f ) : 0;
_b = ops.length === 3 ? rev( ops[2], e, s, g, o, _f ) : rev( ops[1], e, s, g, o, _f );
_c = should_pass_type_info && (wh.hn( _a ) === 'h' || wh.hn( _b ) === 'h');
_d = _c ? wh.rv( _a ) - wh.rv( _b ) : _a - _b;
return _c ? wh.nh( _d, "c" ) : _d;
break;
case '!':
_a = rev( ops[1], e, s, g, o, _f );
_c = should_pass_type_info && (wh.hn( _a ) == 'h');
_d = !wh.rv(_a);
return _c ? wh.nh( _d, "c" ) : _d;
case '~':
_a = rev( ops[1], e, s, g, o, _f );
_c = should_pass_type_info && (wh.hn( _a ) == 'h');
_d = ~wh.rv(_a);
return _c ? wh.nh( _d, "c" ) : _d;
default:
$gwn('unrecognized op' + rop );
}
}
function rev( ops, e, s, g, o, newap )
{
var op = ops[0];
var _f = false;
if ( typeof newap !== "undefined" ) o.ap = newap;
if( typeof(op)==='object' )
{
var vop=op[0];
var _a, _aa, _b, _bb, _c, _d, _s, _e, _ta, _tb, _td;
switch(vop)
{
case 2:
return ArithmeticEv(ops,e,s,g,o);
break;
case 4: 
return rev( ops[1], e, s, g, o, _f );
break;
case 5: 
switch( ops.length )
{
case 2: 
_a = rev( ops[1],e,s,g,o,_f );
return should_pass_type_info?[_a]:[wh.rv(_a)];
return [_a];
break;
case 1: 
return [];
break;
default:
_a = rev( ops[1],e,s,g,o,_f );
_b = rev( ops[2],e,s,g,o,_f );
_a.push( 
should_pass_type_info ?
_b :
wh.rv( _b )
);
return _a;
break;
}
break;
case 6:
_a = rev(ops[1],e,s,g,o);
var ap = o.ap;
_ta = wh.hn(_a)==='h';
_aa = _ta ? wh.rv(_a) : _a;
o.is_affected |= _ta;
if( should_pass_type_info )
{
if( _aa===null || typeof(_aa) === 'undefined' )
{
return _ta ? wh.nh(undefined, 'e') : undefined;
}
_b = rev(ops[2],e,s,g,o,_f);
_tb = wh.hn(_b) === 'h';
_bb = _tb ? wh.rv(_b) : _b;
o.ap = ap;
o.is_affected |= _tb;
if( _bb===null || typeof(_bb) === 'undefined' || 
_bb === "__proto__" || _bb === "prototype" || _bb === "caller" ) 
{
return (_ta || _tb) ? wh.nh(undefined, 'e') : undefined;
}
_d = _aa[_bb];
if ( typeof _d === 'function' && !ap ) _d = undefined;
_td = wh.hn(_d)==='h';
o.is_affected |= _td;
return (_ta || _tb) ? (_td ? _d : wh.nh(_d, 'e')) : _d;
}
else
{
if( _aa===null || typeof(_aa) === 'undefined' )
{
return undefined;
}
_b = rev(ops[2],e,s,g,o,_f);
_tb = wh.hn(_b) === 'h';
_bb = _tb ? wh.rv(_b) : _b;
o.ap = ap;
o.is_affected |= _tb;
if( _bb===null || typeof(_bb) === 'undefined' || 
_bb === "__proto__" || _bb === "prototype" || _bb === "caller" ) 
{
return undefined;
}
_d = _aa[_bb];
if ( typeof _d === 'function' && !ap ) _d = undefined;
_td = wh.hn(_d)==='h';
o.is_affected |= _td;
return _td ? wh.rv(_d) : _d;
}
case 7: 
switch(ops[1][0])
{
case 11:
o.is_affected |= wh.hn(g)==='h';
return g;
case 3:
_s = wh.rv( s );
_e = wh.rv( e );
_b = ops[1][1];
if (g && g.f && g.f.hasOwnProperty(_b) )
{
_a = g.f;
o.ap = true;
}
else
{
_a = _s && _s.hasOwnProperty(_b) ? 
s : (_e && _e.hasOwnProperty(_b) ? e : undefined );
}
if( should_pass_type_info )
{
if( _a )
{
_ta = wh.hn(_a) === 'h';
_aa = _ta ? wh.rv( _a ) : _a;
_d = _aa[_b];
_td = wh.hn(_d) === 'h';
o.is_affected |= _ta || _td;
_d = _ta && !_td ? wh.nh(_d,'e') : _d;
return _d;
}
}
else
{
if( _a )
{
_ta = wh.hn(_a) === 'h';
_aa = _ta ? wh.rv( _a ) : _a;
_d = _aa[_b];
_td = wh.hn(_d) === 'h';
o.is_affected |= _ta || _td;
return wh.rv(_d);
}
}
return undefined;
}
break;
case 8: 
_a = {};
_a[ops[1]] = rev(ops[2],e,s,g,o,_f);
return _a;
break;
case 9: 
_a = rev(ops[1],e,s,g,o,_f);
_b = rev(ops[2],e,s,g,o,_f);
function merge( _a, _b, _ow )
{
var ka, _bbk;
_ta = wh.hn(_a)==='h';
_tb = wh.hn(_b)==='h';
_aa = wh.rv(_a);
_bb = wh.rv(_b);
for(var k in _bb)
{
if ( _ow || !_aa.hasOwnProperty(k) )
{
_aa[k] = should_pass_type_info ? (_tb ? wh.nh(_bb[k],'e') : _bb[k]) : wh.rv(_bb[k]);
}
}
return _a;
}
var _c = _a
var _ow = true
if ( typeof(ops[1][0]) === "object" && ops[1][0][0] === 10 ) {
_a = _b
_b = _c
_ow = false
}
if ( typeof(ops[1][0]) === "object" && ops[1][0][0] === 10 ) {
var _r = {}
return merge( merge( _r, _a, _ow ), _b, _ow );
}
else
return merge( _a, _b, _ow );
break;
case 10:
_a = rev(ops[1],e,s,g,o,_f);
_a = should_pass_type_info ? _a : wh.rv( _a );
return _a ;
break;
case 12:
var _r;
_a = rev(ops[1],e,s,g,o);
if ( !o.ap )
{
return should_pass_type_info && wh.hn(_a)==='h' ? wh.nh( _r, 'f' ) : _r;
}
var ap = o.ap;
_b = rev(ops[2],e,s,g,o,_f);
o.ap = ap;
_ta = wh.hn(_a)==='h';
_tb = _ca(_b);
_aa = wh.rv(_a);	
_bb = wh.rv(_b); snap_bb=$gdc(_bb,"nv_");
try{
_r = typeof _aa === "function" ? $gdc(_aa.apply(null, snap_bb)) : undefined;
} catch (e){
e.message = e.message.replace(/nv_/g,"");
e.stack = e.stack.substring(0,e.stack.indexOf("\n", e.stack.lastIndexOf("at nv_")));
e.stack = e.stack.replace(/\snv_/g," "); 
e.stack = $gstack(e.stack);	
if(g.debugInfo)
e.stack += "\n "+" "+" "+" at "+g.debugInfo[0]+":"+g.debugInfo[1]+":"+g.debugInfo[2];
throw e;
}
return should_pass_type_info && (_tb || _ta) ? wh.nh( _r, 'f' ) : _r;
}
}
else
{
if( op === 3 || op === 1) return ops[1];
else if( op === 11 ) 
{
var _a='';
for( var i = 1 ; i < ops.length ; i++ )
{
var xp = wh.rv(rev(ops[i],e,s,g,o,_f));
_a += typeof(xp) === 'undefined' ? '' : xp;
}
return _a;
}
}
}
function wrapper( ops, e, s, g, o, newap )
{
if( ops[0] == '11182016' )
{
g.debugInfo = ops[2];
return rev( ops[1], e, s, g, o, newap );
}
else
{
g.debugInfo = null;
return rev( ops, e, s, g, o, newap );
}
}
return wrapper;
}
gra=$gwrt(true); 
grb=$gwrt(false); 
function TestTest( expr, ops, e,s,g, expect_a, expect_b, expect_affected )
{
{
var o = {is_affected:false};
var a = gra( ops, e,s,g, o );
if( JSON.stringify(a) != JSON.stringify( expect_a )
|| o.is_affected != expect_affected )
{
console.warn( "A. " + expr + " get result " + JSON.stringify(a) + ", " + o.is_affected + ", but " + JSON.stringify( expect_a ) + ", " + expect_affected + " is expected" );
}
}
{
var o = {is_affected:false};
var a = grb( ops, e,s,g, o );
if( JSON.stringify(a) != JSON.stringify( expect_b )
|| o.is_affected != expect_affected )
{
console.warn( "B. " + expr + " get result " + JSON.stringify(a) + ", " + o.is_affected + ", but " + JSON.stringify( expect_b ) + ", " + expect_affected + " is expected" );
}
}
}

function wfor( to_iter, func, env, _s, global, father, itemname, indexname, keyname )
{
var _n = wh.hn( to_iter ) === 'n'; 
var scope = wh.rv( _s ); 
var has_old_item = scope.hasOwnProperty(itemname);
var has_old_index = scope.hasOwnProperty(indexname);
var old_item = scope[itemname];
var old_index = scope[indexname];
var full = Object.prototype.toString.call(wh.rv(to_iter));
var type = full[8]; 
if( type === 'N' && full[10] === 'l' ) type = 'X'; 
var _y;
if( _n )
{
if( type === 'A' ) 
{
var r_iter_item;
for( var i = 0 ; i < to_iter.length ; i++ )
{
scope[itemname] = to_iter[i];
scope[indexname] = _n ? i : wh.nh(i, 'h');
r_iter_item = wh.rv(to_iter[i]);
var key = keyname && r_iter_item ? (keyname==="*this" ? r_iter_item : wh.rv(r_iter_item[keyname])) : undefined;
_y = _v(key);
_(father,_y);
func( env, scope, _y, global );
}
}
else if( type === 'O' ) 
{
var i = 0;
var r_iter_item;
for( var k in to_iter )
{
scope[itemname] = to_iter[k];
scope[indexname] = _n ? k : wh.nh(k, 'h');
r_iter_item = wh.rv(to_iter[k]);
var key = keyname && r_iter_item ? (keyname==="*this" ? r_iter_item : wh.rv(r_iter_item[keyname])) : undefined;
_y = _v(key);
_(father,_y);
func( env,scope,_y,global );
i++;
}
}
else if( type === 'S' ) 
{
for( var i = 0 ; i < to_iter.length ; i++ )
{
scope[itemname] = to_iter[i];
scope[indexname] = _n ? i : wh.nh(i, 'h');
_y = _v( to_iter[i] + i );
_(father,_y);
func( env,scope,_y,global );
}
}
else if( type === 'N' ) 
{
for( var i = 0 ; i < to_iter ; i++ )
{
scope[itemname] = i;
scope[indexname] = _n ? i : wh.nh(i, 'h');
_y = _v( i );
_(father,_y);
func(env,scope,_y,global);
}
}
else
{
}
}
else
{
var r_to_iter = wh.rv(to_iter);
var r_iter_item, iter_item;
if( type === 'A' ) 
{
for( var i = 0 ; i < r_to_iter.length ; i++ )
{
iter_item = r_to_iter[i];
iter_item = wh.hn(iter_item)==='n' ? wh.nh(iter_item,'h') : iter_item;
r_iter_item = wh.rv( iter_item );
scope[itemname] = iter_item
scope[indexname] = _n ? i : wh.nh(i, 'h');
var key = keyname && r_iter_item ? (keyname==="*this" ? r_iter_item : wh.rv(r_iter_item[keyname])) : undefined;
_y = _v(key);
_(father,_y);
func( env, scope, _y, global );
}
}
else if( type === 'O' ) 
{
var i=0;
for( var k in r_to_iter )
{
iter_item = r_to_iter[k];
iter_item = wh.hn(iter_item)==='n'? wh.nh(iter_item,'h') : iter_item;
r_iter_item = wh.rv( iter_item );
scope[itemname] = iter_item;
scope[indexname] = _n ? k : wh.nh(k, 'h');
var key = keyname && r_iter_item ? (keyname==="*this" ? r_iter_item : wh.rv(r_iter_item[keyname])) : undefined;
_y=_v(key);
_(father,_y);
func( env, scope, _y, global );
i++
}
}
else if( type === 'S' ) 
{
for( var i = 0 ; i < r_to_iter.length ; i++ )
{
iter_item = wh.nh(r_to_iter[i],'h');
scope[itemname] = iter_item;
scope[indexname] = _n ? i : wh.nh(i, 'h');
_y = _v( to_iter[i] + i );
_(father,_y);
func( env, scope, _y, global );
}
}
else if( type === 'N' ) 
{
for( var i = 0 ; i < r_to_iter ; i++ )
{
iter_item = wh.nh(i,'h');
scope[itemname] = iter_item;
scope[indexname]= _n ? i : wh.nh(i,'h');
_y = _v( i );
_(father,_y);
func(env,scope,_y,global);
}
}
else
{
}
}
if(has_old_item)
{
scope[itemname]=old_item;
}
else
{
delete scope[itemname];
}
if(has_old_index)
{
scope[indexname]=old_index;
}
else
{
delete scope[indexname];
}
}

function _ca(o)
{ 
if ( wh.hn(o) == 'h' ) return true;
if ( typeof o !== "object" ) return false;
for(var i in o){ 
if ( o.hasOwnProperty(i) ){
if (_ca(o[i])) return true;
}
}
return false;
}
function _da( node, attrname, opindex, raw, o )
{
var isaffected = false;
if ( o.is_affected || _ca(raw) ) 
{
node.n.push( attrname );
node.raw[attrname] = raw;
var value = $gdc( raw, "", 2 );
return value;
}
else
{
var value = $gdc( raw, "", 2 );
return value;
}
}
function _r( node, attrname, opindex, env, scope, global ) 
{
global.opindex=opindex;
var o = {}, _env;
var a = grb( z[opindex], env, scope, global, o );
a = _da( node, attrname, opindex, a, o );
node.attr[attrname] = a;
}
function _rz( z, node, attrname, opindex, env, scope, global ) 
{
global.opindex=opindex;
var o = {}, _env;
var a = grb( z[opindex], env, scope, global, o );
a = _da( node, attrname, opindex, a, o );
node.attr[attrname] = a;
}
function _o( opindex, env, scope, global )
{
global.opindex=opindex;
var nothing = {};
var r = grb( z[opindex], env, scope, global, nothing );
return (r&&r.constructor===Function) ? undefined : r;
}
function _oz( z, opindex, env, scope, global )
{
global.opindex=opindex;
var nothing = {};
var r = grb( z[opindex], env, scope, global, nothing );
return (r&&r.constructor===Function) ? undefined : r;
}
function _1( opindex, env, scope, global, o )
{
var o = o || {};
global.opindex=opindex;
return gra( z[opindex], env, scope, global, o );
}
function _1z( z, opindex, env, scope, global, o )
{
var o = o || {};
global.opindex=opindex;
return gra( z[opindex], env, scope, global, o );
}
function _2( opindex, func, env, scope, global, father, itemname, indexname, keyname )
{
var o = {};
var to_iter = _1( opindex, env, scope, global );
wfor( to_iter, func, env, scope, global, father, itemname, indexname, keyname );
}
function _2z( z, opindex, func, env, scope, global, father, itemname, indexname, keyname )
{
var o = {};
var to_iter = _1z( z, opindex, env, scope, global );
wfor( to_iter, func, env, scope, global, father, itemname, indexname, keyname );
}


function _m(tag,attrs,generics,env,scope,global)
{
var tmp=_n(tag);
var base=0;
for(var i = 0 ; i < attrs.length ; i+=2 )
{
if(base+attrs[i+1]<0)
{
tmp.attr[attrs[i]]=true;
}
else
{
_r(tmp,attrs[i],base+attrs[i+1],env,scope,global);
if(base===0)base=attrs[i+1];
}
}
for(var i=0;i<generics.length;i+=2)
{
if(base+generics[i+1]<0)
{
tmp.generics[generics[i]]="";
}
else
{
var $t=grb(z[base+generics[i+1]],env,scope,global);
if ($t!="") $t="wx-"+$t;
tmp.generics[generics[i]]=$t;
if(base===0)base=generics[i+1];
}
}
return tmp;
}
function _mz(z,tag,attrs,generics,env,scope,global)
{
var tmp=_n(tag);
var base=0;
for(var i = 0 ; i < attrs.length ; i+=2 )
{
if(base+attrs[i+1]<0)
{
tmp.attr[attrs[i]]=true;
}
else
{
_rz(z, tmp,attrs[i],base+attrs[i+1],env,scope,global);
if(base===0)base=attrs[i+1];
}
}
for(var i=0;i<generics.length;i+=2)
{
if(base+generics[i+1]<0)
{
tmp.generics[generics[i]]="";
}
else
{
var $t=grb(z[base+generics[i+1]],env,scope,global);
if ($t!="") $t="wx-"+$t;
tmp.generics[generics[i]]=$t;
if(base===0)base=generics[i+1];
}
}
return tmp;
}

var nf_init=function(){
if(typeof __swan_GLOBAL__==="undefined"||undefined===__swan_GLOBAL__.wxs_nf_init){
nf_init_Object();nf_init_Function();nf_init_Array();nf_init_String();nf_init_Boolean();nf_init_Number();nf_init_Math();nf_init_Date();nf_init_RegExp();
}
if(typeof __swan_GLOBAL__!=="undefined") __swan_GLOBAL__.wxs_nf_init=true;
};
var nf_init_Object=function(){
Object.defineProperty(Object.prototype,"nv_constructor",{writable:true,value:"Object"})
Object.defineProperty(Object.prototype,"nv_toString",{writable:true,value:function(){return "[object Object]"}})
}
var nf_init_Function=function(){
Object.defineProperty(Function.prototype,"nv_constructor",{writable:true,value:"Function"})
Object.defineProperty(Function.prototype,"nv_length",{get:function(){return this.length;},set:function(){}});
Object.defineProperty(Function.prototype,"nv_toString",{writable:true,value:function(){return "[function Function]"}})
}
var nf_init_Array=function(){
Object.defineProperty(Array.prototype,"nv_toString",{writable:true,value:function(){return this.nv_join();}})
Object.defineProperty(Array.prototype,"nv_join",{writable:true,value:function(s){
s=undefined==s?',':s;
var r="";
for(var i=0;i<this.length;++i){
if(0!=i) r+=s;
if(null==this[i]||undefined==this[i]) r+='';	
else if(typeof this[i]=='function') r+=this[i].nv_toString();
else if(typeof this[i]=='object'&&this[i].nv_constructor==="Array") r+=this[i].nv_join();
else r+=this[i].toString();
}
return r;
}})
Object.defineProperty(Array.prototype,"nv_constructor",{writable:true,value:"Array"})
Object.defineProperty(Array.prototype,"nv_concat",{writable:true,value:Array.prototype.concat})
Object.defineProperty(Array.prototype,"nv_pop",{writable:true,value:Array.prototype.pop})
Object.defineProperty(Array.prototype,"nv_push",{writable:true,value:Array.prototype.push})
Object.defineProperty(Array.prototype,"nv_reverse",{writable:true,value:Array.prototype.reverse})
Object.defineProperty(Array.prototype,"nv_shift",{writable:true,value:Array.prototype.shift})
Object.defineProperty(Array.prototype,"nv_slice",{writable:true,value:Array.prototype.slice})
Object.defineProperty(Array.prototype,"nv_sort",{writable:true,value:Array.prototype.sort})
Object.defineProperty(Array.prototype,"nv_splice",{writable:true,value:Array.prototype.splice})
Object.defineProperty(Array.prototype,"nv_unshift",{writable:true,value:Array.prototype.unshift})
Object.defineProperty(Array.prototype,"nv_indexOf",{writable:true,value:Array.prototype.indexOf})
Object.defineProperty(Array.prototype,"nv_lastIndexOf",{writable:true,value:Array.prototype.lastIndexOf})
Object.defineProperty(Array.prototype,"nv_every",{writable:true,value:Array.prototype.every})
Object.defineProperty(Array.prototype,"nv_some",{writable:true,value:Array.prototype.some})
Object.defineProperty(Array.prototype,"nv_forEach",{writable:true,value:Array.prototype.forEach})
Object.defineProperty(Array.prototype,"nv_map",{writable:true,value:Array.prototype.map})
Object.defineProperty(Array.prototype,"nv_filter",{writable:true,value:Array.prototype.filter})
Object.defineProperty(Array.prototype,"nv_reduce",{writable:true,value:Array.prototype.reduce})
Object.defineProperty(Array.prototype,"nv_reduceRight",{writable:true,value:Array.prototype.reduceRight})
Object.defineProperty(Array.prototype,"nv_length",{get:function(){return this.length;},set:function(value){this.length=value;}});
}
var nf_init_String=function(){
Object.defineProperty(String.prototype,"nv_constructor",{writable:true,value:"String"})
Object.defineProperty(String.prototype,"nv_toString",{writable:true,value:String.prototype.toString})
Object.defineProperty(String.prototype,"nv_valueOf",{writable:true,value:String.prototype.valueOf})
Object.defineProperty(String.prototype,"nv_charAt",{writable:true,value:String.prototype.charAt})
Object.defineProperty(String.prototype,"nv_charCodeAt",{writable:true,value:String.prototype.charCodeAt})
Object.defineProperty(String.prototype,"nv_concat",{writable:true,value:String.prototype.concat})
Object.defineProperty(String.prototype,"nv_indexOf",{writable:true,value:String.prototype.indexOf})
Object.defineProperty(String.prototype,"nv_lastIndexOf",{writable:true,value:String.prototype.lastIndexOf})
Object.defineProperty(String.prototype,"nv_localeCompare",{writable:true,value:String.prototype.localeCompare})
Object.defineProperty(String.prototype,"nv_match",{writable:true,value:String.prototype.match})
Object.defineProperty(String.prototype,"nv_replace",{writable:true,value:String.prototype.replace})
Object.defineProperty(String.prototype,"nv_search",{writable:true,value:String.prototype.search})
Object.defineProperty(String.prototype,"nv_slice",{writable:true,value:String.prototype.slice})
Object.defineProperty(String.prototype,"nv_split",{writable:true,value:String.prototype.split})
Object.defineProperty(String.prototype,"nv_substring",{writable:true,value:String.prototype.substring})
Object.defineProperty(String.prototype,"nv_toLowerCase",{writable:true,value:String.prototype.toLowerCase})
Object.defineProperty(String.prototype,"nv_toLocaleLowerCase",{writable:true,value:String.prototype.toLocaleLowerCase})
Object.defineProperty(String.prototype,"nv_toUpperCase",{writable:true,value:String.prototype.toUpperCase})
Object.defineProperty(String.prototype,"nv_toLocaleUpperCase",{writable:true,value:String.prototype.toLocaleUpperCase})
Object.defineProperty(String.prototype,"nv_trim",{writable:true,value:String.prototype.trim})
Object.defineProperty(String.prototype,"nv_length",{get:function(){return this.length;},set:function(value){this.length=value;}});
}
var nf_init_Boolean=function(){
Object.defineProperty(Boolean.prototype,"nv_constructor",{writable:true,value:"Boolean"})
Object.defineProperty(Boolean.prototype,"nv_toString",{writable:true,value:Boolean.prototype.toString})
Object.defineProperty(Boolean.prototype,"nv_valueOf",{writable:true,value:Boolean.prototype.valueOf})
}
var nf_init_Number=function(){
Object.defineProperty(Number,"nv_MAX_VALUE",{writable:false,value:Number.MAX_VALUE})
Object.defineProperty(Number,"nv_MIN_VALUE",{writable:false,value:Number.MIN_VALUE})
Object.defineProperty(Number,"nv_NEGATIVE_INFINITY",{writable:false,value:Number.NEGATIVE_INFINITY})
Object.defineProperty(Number,"nv_POSITIVE_INFINITY",{writable:false,value:Number.POSITIVE_INFINITY})
Object.defineProperty(Number.prototype,"nv_constructor",{writable:true,value:"Number"})
Object.defineProperty(Number.prototype,"nv_toString",{writable:true,value:Number.prototype.toString})
Object.defineProperty(Number.prototype,"nv_toLocaleString",{writable:true,value:Number.prototype.toLocaleString})
Object.defineProperty(Number.prototype,"nv_valueOf",{writable:true,value:Number.prototype.valueOf})
Object.defineProperty(Number.prototype,"nv_toFixed",{writable:true,value:Number.prototype.toFixed})
Object.defineProperty(Number.prototype,"nv_toExponential",{writable:true,value:Number.prototype.toExponential})
Object.defineProperty(Number.prototype,"nv_toPrecision",{writable:true,value:Number.prototype.toPrecision})
}
var nf_init_Math=function(){
Object.defineProperty(Math,"nv_E",{writable:false,value:Math.E})
Object.defineProperty(Math,"nv_LN10",{writable:false,value:Math.LN10})
Object.defineProperty(Math,"nv_LN2",{writable:false,value:Math.LN2})
Object.defineProperty(Math,"nv_LOG2E",{writable:false,value:Math.LOG2E})
Object.defineProperty(Math,"nv_LOG10E",{writable:false,value:Math.LOG10E})
Object.defineProperty(Math,"nv_PI",{writable:false,value:Math.PI})
Object.defineProperty(Math,"nv_SQRT1_2",{writable:false,value:Math.SQRT1_2})
Object.defineProperty(Math,"nv_SQRT2",{writable:false,value:Math.SQRT2})
Object.defineProperty(Math,"nv_abs",{writable:false,value:Math.abs})
Object.defineProperty(Math,"nv_acos",{writable:false,value:Math.acos})
Object.defineProperty(Math,"nv_asin",{writable:false,value:Math.asin})
Object.defineProperty(Math,"nv_atan",{writable:false,value:Math.atan})
Object.defineProperty(Math,"nv_atan2",{writable:false,value:Math.atan2})
Object.defineProperty(Math,"nv_ceil",{writable:false,value:Math.ceil})
Object.defineProperty(Math,"nv_cos",{writable:false,value:Math.cos})
Object.defineProperty(Math,"nv_exp",{writable:false,value:Math.exp})
Object.defineProperty(Math,"nv_floor",{writable:false,value:Math.floor})
Object.defineProperty(Math,"nv_log",{writable:false,value:Math.log})
Object.defineProperty(Math,"nv_max",{writable:false,value:Math.max})
Object.defineProperty(Math,"nv_min",{writable:false,value:Math.min})
Object.defineProperty(Math,"nv_pow",{writable:false,value:Math.pow})
Object.defineProperty(Math,"nv_random",{writable:false,value:Math.random})
Object.defineProperty(Math,"nv_round",{writable:false,value:Math.round})
Object.defineProperty(Math,"nv_sin",{writable:false,value:Math.sin})
Object.defineProperty(Math,"nv_sqrt",{writable:false,value:Math.sqrt})
Object.defineProperty(Math,"nv_tan",{writable:false,value:Math.tan})
}
var nf_init_Date=function(){
Object.defineProperty(Date.prototype,"nv_constructor",{writable:true,value:"Date"})
Object.defineProperty(Date,"nv_parse",{writable:true,value:Date.parse})
Object.defineProperty(Date,"nv_UTC",{writable:true,value:Date.UTC})
Object.defineProperty(Date,"nv_now",{writable:true,value:Date.now})
Object.defineProperty(Date.prototype,"nv_toString",{writable:true,value:Date.prototype.toString})
Object.defineProperty(Date.prototype,"nv_toDateString",{writable:true,value:Date.prototype.toDateString})
Object.defineProperty(Date.prototype,"nv_toTimeString",{writable:true,value:Date.prototype.toTimeString})
Object.defineProperty(Date.prototype,"nv_toLocaleString",{writable:true,value:Date.prototype.toLocaleString})
Object.defineProperty(Date.prototype,"nv_toLocaleDateString",{writable:true,value:Date.prototype.toLocaleDateString})
Object.defineProperty(Date.prototype,"nv_toLocaleTimeString",{writable:true,value:Date.prototype.toLocaleTimeString})
Object.defineProperty(Date.prototype,"nv_valueOf",{writable:true,value:Date.prototype.valueOf})
Object.defineProperty(Date.prototype,"nv_getTime",{writable:true,value:Date.prototype.getTime})
Object.defineProperty(Date.prototype,"nv_getFullYear",{writable:true,value:Date.prototype.getFullYear})
Object.defineProperty(Date.prototype,"nv_getUTCFullYear",{writable:true,value:Date.prototype.getUTCFullYear})
Object.defineProperty(Date.prototype,"nv_getMonth",{writable:true,value:Date.prototype.getMonth})
Object.defineProperty(Date.prototype,"nv_getUTCMonth",{writable:true,value:Date.prototype.getUTCMonth})
Object.defineProperty(Date.prototype,"nv_getDate",{writable:true,value:Date.prototype.getDate})
Object.defineProperty(Date.prototype,"nv_getUTCDate",{writable:true,value:Date.prototype.getUTCDate})
Object.defineProperty(Date.prototype,"nv_getDay",{writable:true,value:Date.prototype.getDay})
Object.defineProperty(Date.prototype,"nv_getUTCDay",{writable:true,value:Date.prototype.getUTCDay})
Object.defineProperty(Date.prototype,"nv_getHours",{writable:true,value:Date.prototype.getHours})
Object.defineProperty(Date.prototype,"nv_getUTCHours",{writable:true,value:Date.prototype.getUTCHours})
Object.defineProperty(Date.prototype,"nv_getMinutes",{writable:true,value:Date.prototype.getMinutes})
Object.defineProperty(Date.prototype,"nv_getUTCMinutes",{writable:true,value:Date.prototype.getUTCMinutes})
Object.defineProperty(Date.prototype,"nv_getSeconds",{writable:true,value:Date.prototype.getSeconds})
Object.defineProperty(Date.prototype,"nv_getUTCSeconds",{writable:true,value:Date.prototype.getUTCSeconds})
Object.defineProperty(Date.prototype,"nv_getMilliseconds",{writable:true,value:Date.prototype.getMilliseconds})
Object.defineProperty(Date.prototype,"nv_getUTCMilliseconds",{writable:true,value:Date.prototype.getUTCMilliseconds})
Object.defineProperty(Date.prototype,"nv_getTimezoneOffset",{writable:true,value:Date.prototype.getTimezoneOffset})
Object.defineProperty(Date.prototype,"nv_setTime",{writable:true,value:Date.prototype.setTime})
Object.defineProperty(Date.prototype,"nv_setMilliseconds",{writable:true,value:Date.prototype.setMilliseconds})
Object.defineProperty(Date.prototype,"nv_setUTCMilliseconds",{writable:true,value:Date.prototype.setUTCMilliseconds})
Object.defineProperty(Date.prototype,"nv_setSeconds",{writable:true,value:Date.prototype.setSeconds})
Object.defineProperty(Date.prototype,"nv_setUTCSeconds",{writable:true,value:Date.prototype.setUTCSeconds})
Object.defineProperty(Date.prototype,"nv_setMinutes",{writable:true,value:Date.prototype.setMinutes})
Object.defineProperty(Date.prototype,"nv_setUTCMinutes",{writable:true,value:Date.prototype.setUTCMinutes})
Object.defineProperty(Date.prototype,"nv_setHours",{writable:true,value:Date.prototype.setHours})
Object.defineProperty(Date.prototype,"nv_setUTCHours",{writable:true,value:Date.prototype.setUTCHours})
Object.defineProperty(Date.prototype,"nv_setDate",{writable:true,value:Date.prototype.setDate})
Object.defineProperty(Date.prototype,"nv_setUTCDate",{writable:true,value:Date.prototype.setUTCDate})
Object.defineProperty(Date.prototype,"nv_setMonth",{writable:true,value:Date.prototype.setMonth})
Object.defineProperty(Date.prototype,"nv_setUTCMonth",{writable:true,value:Date.prototype.setUTCMonth})
Object.defineProperty(Date.prototype,"nv_setFullYear",{writable:true,value:Date.prototype.setFullYear})
Object.defineProperty(Date.prototype,"nv_setUTCFullYear",{writable:true,value:Date.prototype.setUTCFullYear})
Object.defineProperty(Date.prototype,"nv_toUTCString",{writable:true,value:Date.prototype.toUTCString})
Object.defineProperty(Date.prototype,"nv_toISOString",{writable:true,value:Date.prototype.toISOString})
Object.defineProperty(Date.prototype,"nv_toJSON",{writable:true,value:Date.prototype.toJSON})
}
var nf_init_RegExp=function(){
Object.defineProperty(RegExp.prototype,"nv_constructor",{writable:true,value:"RegExp"})
Object.defineProperty(RegExp.prototype,"nv_exec",{writable:true,value:RegExp.prototype.exec})
Object.defineProperty(RegExp.prototype,"nv_test",{writable:true,value:RegExp.prototype.test})
Object.defineProperty(RegExp.prototype,"nv_toString",{writable:true,value:RegExp.prototype.toString})
Object.defineProperty(RegExp.prototype,"nv_source",{get:function(){return this.source;},set:function(){}});
Object.defineProperty(RegExp.prototype,"nv_global",{get:function(){return this.global;},set:function(){}});
Object.defineProperty(RegExp.prototype,"nv_ignoreCase",{get:function(){return this.ignoreCase;},set:function(){}});
Object.defineProperty(RegExp.prototype,"nv_multiline",{get:function(){return this.multiline;},set:function(){}});
Object.defineProperty(RegExp.prototype,"nv_lastIndex",{get:function(){return this.lastIndex;},set:function(v){this.lastIndex=v;}});
}
nf_init();
var nv_getDate=function(){var args=Array.prototype.slice.call(arguments);args.unshift(Date);return new(Function.prototype.bind.apply(Date, args));}
var nv_getRegExp=function(){var args=Array.prototype.slice.call(arguments);args.unshift(RegExp);return new(Function.prototype.bind.apply(RegExp, args));}
var nv_console={}
nv_console.nv_log=function(){var res="WXSRT:";for(var i=0;i<arguments.length;++i)res+=arguments[i]+" ";console.log(res);}
var nv_parseInt = parseInt, nv_parseFloat = parseFloat, nv_isNaN = isNaN, nv_isFinite = isFinite, nv_decodeURI = decodeURI, nv_decodeURIComponent = decodeURIComponent, nv_encodeURI = encodeURI, nv_encodeURIComponent = encodeURIComponent;
function $gdc(o,p,r) {
o=wh.rv(o);
if(o===null||o===undefined) return o;
if(o.constructor===String||o.constructor===Boolean||o.constructor===Number) return o;
if(o.constructor===Object){
var copy={};
for(var k in o)
if(o.hasOwnProperty(k))
if(undefined===p) copy[k.substring(3)]=$gdc(o[k],p,r);
else copy[p+k]=$gdc(o[k],p,r);
return copy;
}
if(o.constructor===Array){
var copy=[];
for(var i=0;i<o.length;i++) copy.push($gdc(o[i],p,r));
return copy;
}
if(o.constructor===Date){
var copy=new Date();
copy.setTime(o.getTime());
return copy;
}
if(o.constructor===RegExp){
var f="";
if(o.global) f+="g";
if(o.ignoreCase) f+="i";
if(o.multiline) f+="m";
return (new RegExp(o.source,f));
}
if(r&&o.constructor===Function){
if ( r == 1 ) return $gdc(o(),undefined, 2);
if ( r == 2 ) return o;
}
return null;
}
var nv_JSON={}
nv_JSON.nv_stringify=function(o){
JSON.stringify(o);
return JSON.stringify($gdc(o));
}
nv_JSON.nv_parse=function(o){
if(o===undefined) return undefined;
var t=JSON.parse(o);
return $gdc(t,'nv_');
}

function _af(p, a, c){
p.extraAttr = {"t_action": a, "t_cid": c};
}

function _gv( )
{if( typeof( window.__webview_engine_version__) == 'undefined' ) return 0.0;
return window.__webview_engine_version__;}
function _ai(i,p,e,me,r,c){var x=_grp(p,e,me);if(x)i.push(x);else{i.push('');_wp(me+':import:'+r+':'+c+': Path `'+p+'` not found from `'+me+'`.')}}
function _grp(p,e,me){if(p[0]!='/'){var mepart=me.split('/');mepart.pop();var ppart=p.split('/');for(var i=0;i<ppart.length;i++){if( ppart[i]=='..')mepart.pop();else if(!ppart[i]||ppart[i]=='.')continue;else mepart.push(ppart[i]);}p=mepart.join('/');}if(me[0]=='.'&&p[0]=='/')p='.'+p;if(e[p])return p;if(e[p+'.swan'])return p+'.swan';}
function _gd(p,c,e,d){if(!c)return;if(d[p][c])return d[p][c];for(var x=e[p].i.length-1;x>=0;x--){if(e[p].i[x]&&d[e[p].i[x]][c])return d[e[p].i[x]][c]};for(var x=e[p].ti.length-1;x>=0;x--){var q=_grp(e[p].ti[x],e,p);if(q&&d[q][c])return d[q][c]}var ii=_gapi(e,p);for(var x=0;x<ii.length;x++){if(ii[x]&&d[ii[x]][c])return d[ii[x]][c]}for(var k=e[p].j.length-1;k>=0;k--)if(e[p].j[k]){for(var q=e[e[p].j[k]].ti.length-1;q>=0;q--){var pp=_grp(e[e[p].j[k]].ti[q],e,p);if(pp&&d[pp][c]){return d[pp][c]}}}}
function _gapi(e,p){if(!p)return [];if($gaic[p]){return $gaic[p]};var ret=[],q=[],h=0,t=0,put={},visited={};q.push(p);visited[p]=true;t++;while(h<t){var a=q[h++];for(var i=0;i<e[a].ic.length;i++){var nd=e[a].ic[i];var np=_grp(nd,e,a);if(np&&!visited[np]){visited[np]=true;q.push(np);t++;}}for(var i=0;a!=p&&i<e[a].ti.length;i++){var ni=e[a].ti[i];var nm=_grp(ni,e,a);if(nm&&!put[nm]){put[nm]=true;ret.push(nm);}}}$gaic[p]=ret;return ret;}
var $ixc={};function _ic(p,ent,me,e,s,r,gg){var x=_grp(p,ent,me);ent[me].j.push(x);if(x){if($ixc[x]){_wp('-1:include:-1:-1: `'+p+'` is being included in a loop, will be stop.');return;}$ixc[x]=true;try{ent[x].f(e,s,r,gg)}catch(e){}$ixc[x]=false;}else{_wp(me+':include:-1:-1: Included path `'+p+'` not found from `'+me+'`.')}}
function _w(tn,f,line,c){_wp(f+':template:'+line+':'+c+': Template `'+tn+'` not found.');}function _ev(dom){var changed=false;delete dom.properities;delete dom.n;if(dom.children){do{changed=false;var newch = [];for(var i=0;i<dom.children.length;i++){var ch=dom.children[i];if( ch.tag=='virtual'){changed=true;for(var j=0;ch.children&&j<ch.children.length;j++){newch.push(ch.children[j]);}}else { newch.push(ch); } } dom.children = newch; }while(changed);for(var i=0;i<dom.children.length;i++){_ev(dom.children[i]);}} return dom; }
var e_={}
if(typeof(global.entrys)==='undefined')global.entrys={};e_=global.entrys;
var d_={}
if(typeof(global.defines)==='undefined')global.defines={};d_=global.defines;
var f_={}
if(typeof(global.modules)==='undefined')global.modules={};f_=global.modules;
var p_={}
__swan_GLOBAL__.ops_cached = __swan_GLOBAL__.ops_cached || {}
__swan_GLOBAL__.ops_set = __swan_GLOBAL__.ops_set || {};
__swan_GLOBAL__.ops_init = __swan_GLOBAL__.ops_init || {};
var z=__swan_GLOBAL__.ops_set.$gwx3 || [];
function gz$gwx3_1(){
if( __swan_GLOBAL__.ops_cached.$gwx3_1)return __swan_GLOBAL__.ops_cached.$gwx3_1
__swan_GLOBAL__.ops_cached.$gwx3_1=[];
(function(z){var a=11;function Z(ops){z.push(ops)}
Z([a,[3,'page '],[[7],[3,'__page_classes']]])
Z([3,'body after-navber'])
Z([3,'position:relative;'])
Z([3,'widthFix'])
Z([[6],[[6],[[6],[[6],[[7],[3,'__wxapp_img']],[3,'scratch']],[3,'index']],[3,'scratch_bg']],[3,'url']])
Z([3,'position:absolute;text-align:center;width:100%;'])
Z([3,'navigator-hover'])
Z([3,'/scratch/rule/rule'])
Z([3,'scratch-rule'])
Z([3,'规则'])
Z([3,'showShareModal'])
Z(z[8])
Z([3,'top:104rpx'])
Z([3,'分享'])
Z([3,'scratch-center'])
Z([3,'text-align:center'])
Z([3,'scratch-cj'])
Z([3,'scratch-text'])
Z([3,'您还有'])
Z([3,'scratch-num'])
Z([a,[[7],[3,'oppty']]])
Z([3,'次抽奖机会'])
Z([3,'scratch-bg'])
Z([3,'position:relative'])
Z([3,'scratch-bg-1'])
Z([3,'/scratch/images/scratch_bg.png'])
Z([3,'scratch-bg-2'])
Z([3,'frame'])
Z([3,'/scratch/images/scratch_kuang.png'])
Z([[7],[3,'register']])
Z([3,'scratch-award'])
Z([3,'scratch-award-a'])
Z([[7],[3,'isStart']])
Z([3,'touchEnd'])
Z([3,'touchMove'])
Z([3,'touchStart'])
Z([3,'scratch'])
Z([3,'scratch-canvas'])
Z([[7],[3,'isScroll']])
Z([3,'scratch-bg-text'])
Z([[7],[3,'award_name']])
Z([3,'scratch-text-1'])
Z([a,[[7],[3,'name']]])
Z([[2,'&&'],[[2,'>'],[[7],[3,'oppty']],[1,0]],[[7],[3,'award_name']]])
Z([3,'onStart'])
Z([3,'scratch-bg-text-2'])
Z([3,'再刮一次'])
Z([[2,'&&'],[[2,'<='],[[7],[3,'oppty']],[1,0]],[[7],[3,'award_name']]])
Z([3,'scratch-bg-text-3'])
Z(z[46])
Z([[2,'!'],[[7],[3,'register']]])
Z(z[26])
Z([3,'/scratch/images/scratch_hide_2.png'])
Z(z[50])
Z([3,'register'])
Z([3,'scratch-bg-3'])
Z([a,[[7],[3,'deplete_register']],[3,'积分刮一次']])
Z([3,'padding:40rpx 0;color:#ffffff;'])
Z(z[6])
Z([3,'redirect'])
Z([3,'display:inline;padding-right:150rpx'])
Z([3,'/pages/index/index'])
Z([3,'/scratch/images/home.png'])
Z([3,'height:30rpx;width:30rpx;margin-right:14rpx;margin-bottom:-2rpx'])
Z([3,'回到首页'])
Z(z[6])
Z([3,'display:inline;'])
Z([3,'/scratch/prize/prize'])
Z([3,'\r\n				我的中奖记录  \x3e\x3e\r\n			'])
Z([3,'margin-bottom:20rpx;height:186rpx;width:654rpx;background:#420080;display:inline-block;border-radius:16rpx;text-align:left'])
Z([3,'height:10rpx'])
Z([3,'recommend'])
Z([3,'left'])
Z([3,'中奖名单'])
Z([3,'right'])
Z([3,'true'])
Z([3,'false'])
Z([3,'record'])
Z([3,'2'])
Z([3,'500'])
Z([3,'5000'])
Z([3,'height:85rpx;'])
Z(z[75])
Z([[7],[3,'log']])
Z([3,'text-more-2'])
Z([3,'-webkit-line-clamp:1'])
Z([a,[[6],[[7],[3,'item']],[3,'create_time']],[3,'  '],[[6],[[7],[3,'item']],[3,'user']],[3,'  '],[[6],[[7],[3,'item']],[3,'name']]])
Z([[2,'<'],[[6],[[7],[3,'log']],[3,'length']],[1,2]])
Z([[7],[3,'award']])
Z([3,'model-award'])
Z([3,'act-modal show'])
Z([3,'act-modal-bg'])
Z([3,'act-modal-pic'])
Z([3,'act-modal-start'])
Z([3,'scaleToFill'])
Z([[6],[[6],[[6],[[6],[[7],[3,'__wxapp_img']],[3,'scratch']],[3,'index']],[3,'scratch_success']],[3,'url']])
Z([3,'gx'])
Z([3,'flex-y-center gx-a'])
Z([3,'text-more-2 gx-b'])
Z([3,'恭喜获得'])
Z([a,z[42][1],[3,'\r\n						']])
Z([3,'act-modal-end'])
Z(z[44])
Z([3,'act-modal-k'])
Z([3,'\r\n					再刮一次\r\n				'])
Z([3,'act-zh'])
Z([3,'奖品已放入您的账号'])
Z([a,[3,'goods-qrcode-modal '],[[7],[3,'qrcode_active']]])
Z([3,'goods-qrcode-body flex-col'])
Z([3,'flex-grow-1'])
Z([3,'position: relative'])
Z([3,'position: absolute;left: 0;top:0;width: 100%;height: 100%;padding: 100rpx 100rpx 60rpx'])
Z([3,'goods-qrcode-box'])
Z([3,'goods-qrcode-loading flex-x-center flex-y-center'])
Z([3,'flex-x-center flex-col'])
Z([[6],[[6],[[6],[[7],[3,'__wxapp_img']],[3,'system']],[3,'loading2']],[3,'url']])
Z([3,'width: 150rpx;height: 150rpx'])
Z([3,'color: #888'])
Z([3,'海报生成中'])
Z([3,'qrcodeClick'])
Z([a,[3,'goods-qrcode '],[[2,'?:'],[[7],[3,'goods_qrcode']],[1,'active'],[1,'']]])
Z([[7],[3,'goods_qrcode']])
Z([3,'aspectFit'])
Z(z[121])
Z([3,'flex-grow-0 flex-col flex-x-center'])
Z([3,'padding: 0 60rpx 80rpx'])
Z([3,'margin-bottom: 20rpx;padding: 0 40rpx'])
Z(z[121])
Z([3,'saveQrcode'])
Z([3,'background: #ff4544;color: #fff;'])
Z([3,'\r\n                    保存图片\r\n                '])
Z([3,'opacity: .4'])
Z([3,'保存图片'])
Z([3,'color: #888;font-size: 9pt;text-align: center'])
Z([3,'保存至相册可以分享到朋友圈'])
Z([3,'qrcodeClose'])
Z([3,'goods-qrcode-close'])
Z([[6],[[6],[[6],[[7],[3,'__wxapp_img']],[3,'store']],[3,'close2']],[3,'url']])
Z([3,'width: 50rpx;height: 50rpx;display: block'])
Z([a,[3,'share-modal '],[[7],[3,'share_modal_active']]])
Z([3,'share-modal-body'])
Z([3,'flex-row'])
Z([3,'flex-grow-1 flex-x-center'])
Z([3,'share-bottom'])
Z([3,'share'])
Z([[6],[[6],[[6],[[7],[3,'__wxapp_img']],[3,'share']],[3,'friend']],[3,'url']])
Z([3,'分享给朋友'])
Z(z[142])
Z([3,'getGoodsQrcode'])
Z(z[143])
Z([[6],[[6],[[6],[[7],[3,'__wxapp_img']],[3,'share']],[3,'qrcode']],[3,'url']])
Z([3,'生成商品海报'])
Z([3,'shareModalClose'])
Z([3,'share-modal-close flex-y-center flex-x-center'])
Z([3,'关闭'])
})(__swan_GLOBAL__.ops_cached.$gwx3_1);return __swan_GLOBAL__.ops_cached.$gwx3_1
}
function gz$gwx3_2(){
if( __swan_GLOBAL__.ops_cached.$gwx3_2)return __swan_GLOBAL__.ops_cached.$gwx3_2
__swan_GLOBAL__.ops_cached.$gwx3_2=[];
(function(z){var a=11;function Z(ops){z.push(ops)}
Z([[2,'||'],[[2,'!'],[[7],[3,'list']]],[[2,'=='],[[6],[[7],[3,'list']],[3,'length']],[1,0]]])
Z([3,'no-content'])
Z([3,'暂无中奖记录'])
Z([[7],[3,'list']])
Z([3,'prize-top'])
Z([3,'prize-cen'])
Z([3,'prize-goods'])
Z([a,[[6],[[7],[3,'item']],[1,'name']]])
Z([3,'prize-time'])
Z([a,[[6],[[7],[3,'item']],[1,'create_time']]])
Z([[2,'&&'],[[2,'=='],[[6],[[7],[3,'item']],[1,'status']],[1,1]],[[2,'=='],[[6],[[7],[3,'item']],[1,'type']],[1,4]]])
Z([3,'submit'])
Z([3,'prize-start'])
Z([[6],[[7],[3,'item']],[3,'attr']])
Z([[6],[[7],[3,'item']],[3,'gift_id']])
Z([[6],[[7],[3,'item']],[3,'id']])
Z([3,'立即兑换'])
Z([[2,'&&'],[[2,'=='],[[6],[[7],[3,'item']],[1,'status']],[1,2]],[[2,'=='],[[6],[[7],[3,'item']],[1,'type']],[1,4]]])
Z([3,'prize-end'])
Z([3,'已兑换'])
Z([[2,'&&'],[[2,'=='],[[6],[[7],[3,'item']],[1,'status']],[1,2]],[[2,'!='],[[6],[[7],[3,'item']],[1,'type']],[1,4]]])
Z(z[18])
Z([3,'已发放'])
Z([3,'prize-line'])
})(__swan_GLOBAL__.ops_cached.$gwx3_2);return __swan_GLOBAL__.ops_cached.$gwx3_2
}
function gz$gwx3_3(){
if( __swan_GLOBAL__.ops_cached.$gwx3_3)return __swan_GLOBAL__.ops_cached.$gwx3_3
__swan_GLOBAL__.ops_cached.$gwx3_3=[];
(function(z){var a=11;function Z(ops){z.push(ops)}
Z([3,'padding:30rpx'])
Z([a,[[7],[3,'rule']]])
})(__swan_GLOBAL__.ops_cached.$gwx3_3);return __swan_GLOBAL__.ops_cached.$gwx3_3
}
__swan_GLOBAL__.ops_set.$gwx3=z;
__swan_GLOBAL__.ops_init.$gwx3=true;
var nv_require=function(){var nnm={};var nom={};return function(n){return function(){if(!nnm[n]) return undefined;try{if(!nom[n])nom[n]=nnm[n]();return nom[n];}catch(e){e.message=e.message.replace(/nv_/g,'');var tmp = e.stack.substring(0,e.stack.lastIndexOf(n));e.stack = tmp.substring(0,tmp.lastIndexOf('\n'));e.stack = e.stack.replace(/\snv_/g,' ');e.stack = $gstack(e.stack);e.stack += '\n    at ' + n.substring(2);throw e;}
}}}()
var x=['./scratch/index/index.swan','/commons/header/header.swan','/commons/footer/footer.swan','./scratch/prize/prize.swan','./scratch/rule/rule.swan'];d_[x[0]]={}
var m0=function(e,s,r,gg){
var z=gz$gwx3_1()
var oB=_n('view')
_rz(z,oB,'class',0,e,s,gg)
var xC=e_[x[0]].j
_ic(x[1],e_,x[0],e,s,oB,gg);
var oD=_n('view')
_rz(z,oD,'class',1,e,s,gg)
var cF=_n('view')
_rz(z,cF,'style',2,e,s,gg)
var hG=_mz(z,'image',['mode',3,'src',1,'style',2],[],e,s,gg)
_(cF,hG)
var oH=_mz(z,'navigator',['hoverClass',6,'url',1],[],e,s,gg)
var cI=_n('view')
_rz(z,cI,'class',8,e,s,gg)
var oJ=_oz(z,9,e,s,gg)
_(cI,oJ)
_(oH,cI)
_(cF,oH)
var lK=_mz(z,'view',['bindtap',10,'class',1,'style',2],[],e,s,gg)
var aL=_oz(z,13,e,s,gg)
_(lK,aL)
_(cF,lK)
var tM=_mz(z,'view',['class',14,'style',1],[],e,s,gg)
var eN=_n('view')
var bO=_n('view')
_rz(z,bO,'class',16,e,s,gg)
var oP=_n('view')
_rz(z,oP,'class',17,e,s,gg)
var xQ=_oz(z,18,e,s,gg)
_(oP,xQ)
var oR=_n('text')
_rz(z,oR,'class',19,e,s,gg)
var fS=_oz(z,20,e,s,gg)
_(oR,fS)
_(oP,oR)
var cT=_oz(z,21,e,s,gg)
_(oP,cT)
_(bO,oP)
_(eN,bO)
var hU=_n('view')
_rz(z,hU,'class',22,e,s,gg)
var oV=_n('view')
_rz(z,oV,'style',23,e,s,gg)
var aZ=_mz(z,'image',['class',24,'src',1],[],e,s,gg)
_(oV,aZ)
var t1=_mz(z,'image',['class',26,'id',1,'src',2],[],e,s,gg)
_(oV,t1)
var cW=_v()
_(oV,cW)
if(_oz(z,29,e,s,gg)){cW.wxVkey=1
var e2=_n('view')
_rz(z,e2,'class',30,e,s,gg)
var b3=_n('view')
_rz(z,b3,'class',31,e,s,gg)
var o4=_v()
_(b3,o4)
if(_oz(z,32,e,s,gg)){o4.wxVkey=1
var x5=_mz(z,'canvas',['bindtouchend',33,'bindtouchmove',1,'bindtouchstart',2,'canvasId',3,'class',4,'disableScroll',5],[],e,s,gg)
_(o4,x5)
}
var o6=_n('view')
_rz(z,o6,'class',39,e,s,gg)
var f7=_v()
_(o6,f7)
if(_oz(z,40,e,s,gg)){f7.wxVkey=1
var o0=_n('text')
_rz(z,o0,'class',41,e,s,gg)
var cAB=_oz(z,42,e,s,gg)
_(o0,cAB)
_(f7,o0)
}
var c8=_v()
_(o6,c8)
if(_oz(z,43,e,s,gg)){c8.wxVkey=1
var oBB=_mz(z,'view',['bindtap',44,'class',1],[],e,s,gg)
var lCB=_oz(z,46,e,s,gg)
_(oBB,lCB)
_(c8,oBB)
}
var h9=_v()
_(o6,h9)
if(_oz(z,47,e,s,gg)){h9.wxVkey=1
var aDB=_n('view')
_rz(z,aDB,'class',48,e,s,gg)
var tEB=_oz(z,49,e,s,gg)
_(aDB,tEB)
_(h9,aDB)
}
f7.wxXCkey=1
c8.wxXCkey=1
h9.wxXCkey=1
_(b3,o6)
o4.wxXCkey=1
_(e2,b3)
_(cW,e2)
}
var oX=_v()
_(oV,oX)
if(_oz(z,50,e,s,gg)){oX.wxVkey=1
var eFB=_mz(z,'image',['class',51,'src',1],[],e,s,gg)
_(oX,eFB)
}
var lY=_v()
_(oV,lY)
if(_oz(z,53,e,s,gg)){lY.wxVkey=1
var bGB=_mz(z,'view',['bindtap',54,'class',1],[],e,s,gg)
var oHB=_oz(z,56,e,s,gg)
_(bGB,oHB)
_(lY,bGB)
}
cW.wxXCkey=1
oX.wxXCkey=1
lY.wxXCkey=1
_(hU,oV)
_(eN,hU)
var xIB=_n('view')
_rz(z,xIB,'style',57,e,s,gg)
var oJB=_mz(z,'navigator',['hoverClass',58,'openType',1,'style',2,'url',3],[],e,s,gg)
var fKB=_mz(z,'image',['src',62,'style',1],[],e,s,gg)
_(oJB,fKB)
var cLB=_n('text')
var hMB=_oz(z,64,e,s,gg)
_(cLB,hMB)
_(oJB,cLB)
_(xIB,oJB)
var oNB=_mz(z,'navigator',['hoverClass',65,'style',1,'url',2],[],e,s,gg)
var cOB=_oz(z,68,e,s,gg)
_(oNB,cOB)
_(xIB,oNB)
_(eN,xIB)
var oPB=_n('view')
_rz(z,oPB,'style',69,e,s,gg)
var lQB=_n('view')
_rz(z,lQB,'style',70,e,s,gg)
_(oPB,lQB)
var aRB=_n('view')
_rz(z,aRB,'class',71,e,s,gg)
var tSB=_n('view')
_rz(z,tSB,'class',72,e,s,gg)
_(aRB,tSB)
var eTB=_n('text')
var bUB=_oz(z,73,e,s,gg)
_(eTB,bUB)
_(aRB,eTB)
var oVB=_n('view')
_rz(z,oVB,'class',74,e,s,gg)
_(aRB,oVB)
_(oPB,aRB)
var xWB=_mz(z,'swiper',['autoplay',75,'circular',1,'class',2,'displayMultipleItems',3,'duration',4,'interval',5,'style',6,'vertical',7],[],e,s,gg)
var fYB=_v()
_(xWB,fYB)
var cZB=function(o2B,h1B,c3B,gg){
var l5B=_n('swiper-item')
var a6B=_mz(z,'view',['class',84,'style',1],[],o2B,h1B,gg)
var t7B=_oz(z,86,o2B,h1B,gg)
_(a6B,t7B)
_(l5B,a6B)
_(c3B,l5B)
return c3B
}
fYB.wxXCkey=2
_2z(z,83,cZB,e,s,gg,fYB,'item','index','')
var oXB=_v()
_(xWB,oXB)
if(_oz(z,87,e,s,gg)){oXB.wxVkey=1
var e8B=_n('swiper-item')
_(oXB,e8B)
}
oXB.wxXCkey=1
_(oPB,xWB)
_(eN,oPB)
_(tM,eN)
_(cF,tM)
_(oD,cF)
var fE=_v()
_(oD,fE)
if(_oz(z,88,e,s,gg)){fE.wxVkey=1
var b9B=_n('view')
_rz(z,b9B,'class',89,e,s,gg)
var o0B=_n('view')
_rz(z,o0B,'class',90,e,s,gg)
var xAC=_n('view')
_rz(z,xAC,'class',91,e,s,gg)
_(o0B,xAC)
var oBC=_n('view')
_rz(z,oBC,'class',92,e,s,gg)
var fCC=_n('view')
_rz(z,fCC,'class',93,e,s,gg)
var cDC=_mz(z,'image',['mode',94,'src',1],[],e,s,gg)
_(fCC,cDC)
var hEC=_n('view')
_rz(z,hEC,'class',96,e,s,gg)
var oFC=_n('view')
_rz(z,oFC,'class',97,e,s,gg)
var cGC=_n('view')
_rz(z,cGC,'class',98,e,s,gg)
var oHC=_n('view')
var lIC=_oz(z,99,e,s,gg)
_(oHC,lIC)
_(cGC,oHC)
var aJC=_oz(z,100,e,s,gg)
_(cGC,aJC)
_(oFC,cGC)
_(hEC,oFC)
_(fCC,hEC)
_(oBC,fCC)
var tKC=_n('view')
_rz(z,tKC,'class',101,e,s,gg)
var eLC=_mz(z,'view',['bindtap',102,'class',1],[],e,s,gg)
var bMC=_oz(z,104,e,s,gg)
_(eLC,bMC)
_(tKC,eLC)
var oNC=_n('view')
_rz(z,oNC,'class',105,e,s,gg)
var xOC=_oz(z,106,e,s,gg)
_(oNC,xOC)
_(tKC,oNC)
_(oBC,tKC)
_(o0B,oBC)
_(b9B,o0B)
_(fE,b9B)
}
var oPC=_n('view')
_rz(z,oPC,'class',107,e,s,gg)
var fQC=_n('view')
_rz(z,fQC,'class',108,e,s,gg)
var cRC=_mz(z,'view',['class',109,'style',1],[],e,s,gg)
var hSC=_n('view')
_rz(z,hSC,'style',111,e,s,gg)
var oTC=_n('view')
_rz(z,oTC,'class',112,e,s,gg)
var cUC=_n('view')
_rz(z,cUC,'class',113,e,s,gg)
var oVC=_n('view')
_rz(z,oVC,'class',114,e,s,gg)
var lWC=_mz(z,'image',['src',115,'style',1],[],e,s,gg)
_(oVC,lWC)
var aXC=_n('view')
_rz(z,aXC,'style',117,e,s,gg)
var tYC=_oz(z,118,e,s,gg)
_(aXC,tYC)
_(oVC,aXC)
_(cUC,oVC)
_(oTC,cUC)
var eZC=_mz(z,'image',['bindtap',119,'class',1,'data-src',2,'mode',3,'src',4],[],e,s,gg)
_(oTC,eZC)
_(hSC,oTC)
_(cRC,hSC)
_(fQC,cRC)
var b1C=_mz(z,'view',['class',124,'style',1],[],e,s,gg)
var o2C=_n('view')
_rz(z,o2C,'style',126,e,s,gg)
var x3C=_v()
_(o2C,x3C)
if(_oz(z,127,e,s,gg)){x3C.wxVkey=1
var o4C=_mz(z,'button',['bindtap',128,'style',1],[],e,s,gg)
var f5C=_oz(z,130,e,s,gg)
_(o4C,f5C)
_(x3C,o4C)
}
else{x3C.wxVkey=2
var c6C=_n('button')
_rz(z,c6C,'style',131,e,s,gg)
var h7C=_oz(z,132,e,s,gg)
_(c6C,h7C)
_(x3C,c6C)
}
x3C.wxXCkey=1
_(b1C,o2C)
var o8C=_n('view')
_rz(z,o8C,'style',133,e,s,gg)
var c9C=_oz(z,134,e,s,gg)
_(o8C,c9C)
_(b1C,o8C)
_(fQC,b1C)
var o0C=_mz(z,'view',['bindtap',135,'class',1],[],e,s,gg)
var lAD=_mz(z,'image',['src',137,'style',1],[],e,s,gg)
_(o0C,lAD)
_(fQC,o0C)
_(oPC,fQC)
_(oD,oPC)
var aBD=_n('view')
_rz(z,aBD,'class',139,e,s,gg)
var tCD=_n('view')
_rz(z,tCD,'class',140,e,s,gg)
var eDD=_n('view')
_rz(z,eDD,'class',141,e,s,gg)
var bED=_n('view')
_rz(z,bED,'class',142,e,s,gg)
var oFD=_mz(z,'button',['class',143,'openType',1],[],e,s,gg)
var xGD=_n('image')
_rz(z,xGD,'src',145,e,s,gg)
_(oFD,xGD)
var oHD=_n('view')
var fID=_oz(z,146,e,s,gg)
_(oHD,fID)
_(oFD,oHD)
_(bED,oFD)
_(eDD,bED)
var cJD=_n('view')
_rz(z,cJD,'class',147,e,s,gg)
var hKD=_mz(z,'view',['bindtap',148,'class',1],[],e,s,gg)
var oLD=_n('image')
_rz(z,oLD,'src',150,e,s,gg)
_(hKD,oLD)
var cMD=_n('view')
var oND=_oz(z,151,e,s,gg)
_(cMD,oND)
_(hKD,cMD)
_(cJD,hKD)
_(eDD,cJD)
_(tCD,eDD)
var lOD=_mz(z,'view',['bindtap',152,'class',1],[],e,s,gg)
var aPD=_oz(z,154,e,s,gg)
_(lOD,aPD)
_(tCD,lOD)
_(aBD,tCD)
_(oD,aBD)
fE.wxXCkey=1
_(oB,oD)
_ic(x[2],e_,x[0],e,s,oB,gg);
xC.pop()
xC.pop()
_(r,oB)
return r
}
e_[x[0]]={f:m0,j:[],i:[],ti:[],ic:[]}
d_[x[3]]={}
var m1=function(e,s,r,gg){
var z=gz$gwx3_2()
var eRD=_n('view')
var bSD=_v()
_(eRD,bSD)
if(_oz(z,0,e,s,gg)){bSD.wxVkey=1
var oTD=_n('view')
_rz(z,oTD,'class',1,e,s,gg)
var xUD=_oz(z,2,e,s,gg)
_(oTD,xUD)
_(bSD,oTD)
}
var oVD=_v()
_(eRD,oVD)
var fWD=function(hYD,cXD,oZD,gg){
var o2D=_n('view')
_rz(z,o2D,'class',4,hYD,cXD,gg)
var e6D=_n('view')
_rz(z,e6D,'class',5,hYD,cXD,gg)
var b7D=_n('view')
_rz(z,b7D,'class',6,hYD,cXD,gg)
var o8D=_oz(z,7,hYD,cXD,gg)
_(b7D,o8D)
_(e6D,b7D)
var x9D=_n('view')
_rz(z,x9D,'class',8,hYD,cXD,gg)
var o0D=_oz(z,9,hYD,cXD,gg)
_(x9D,o0D)
_(e6D,x9D)
_(o2D,e6D)
var l3D=_v()
_(o2D,l3D)
if(_oz(z,10,hYD,cXD,gg)){l3D.wxVkey=1
var fAE=_mz(z,'view',['bindtap',11,'class',1,'data-attr',2,'data-gift',3,'data-id',4],[],hYD,cXD,gg)
var cBE=_oz(z,16,hYD,cXD,gg)
_(fAE,cBE)
_(l3D,fAE)
}
var a4D=_v()
_(o2D,a4D)
if(_oz(z,17,hYD,cXD,gg)){a4D.wxVkey=1
var hCE=_n('view')
_rz(z,hCE,'class',18,hYD,cXD,gg)
var oDE=_oz(z,19,hYD,cXD,gg)
_(hCE,oDE)
_(a4D,hCE)
}
var t5D=_v()
_(o2D,t5D)
if(_oz(z,20,hYD,cXD,gg)){t5D.wxVkey=1
var cEE=_n('view')
_rz(z,cEE,'class',21,hYD,cXD,gg)
var oFE=_oz(z,22,hYD,cXD,gg)
_(cEE,oFE)
_(t5D,cEE)
}
l3D.wxXCkey=1
a4D.wxXCkey=1
t5D.wxXCkey=1
_(oZD,o2D)
var lGE=_n('view')
_rz(z,lGE,'class',23,hYD,cXD,gg)
_(oZD,lGE)
return oZD
}
oVD.wxXCkey=2
_2z(z,3,fWD,e,s,gg,oVD,'item','index','')
bSD.wxXCkey=1
_(r,eRD)
return r
}
e_[x[3]]={f:m1,j:[],i:[],ti:[],ic:[]}
d_[x[4]]={}
var m2=function(e,s,r,gg){
var z=gz$gwx3_3()
var tIE=_n('view')
_rz(z,tIE,'style',0,e,s,gg)
var eJE=_n('text')
var bKE=_oz(z,1,e,s,gg)
_(eJE,bKE)
_(tIE,eJE)
_(r,tIE)
return r
}
e_[x[4]]={f:m2,j:[],i:[],ti:[],ic:[]}
if(path&&e_[path]){
window.__swan_comp_version__=0.02
return function(env,dd,global){$gwxc=0;var root={"tag":"wx-page"};root.children=[]
var main=e_[path].f
if (typeof global==="undefined")global={};global.f=$gdc(f_[path],"",1);
if(typeof(window.__webview_engine_version__)!='undefined'&&window.__webview_engine_version__+1e-6>=0.02+1e-6&&window.__mergeData__)
{
env=window.__mergeData__(env,dd);
}
try{
main(env,{},root,global);
if(typeof(window.__webview_engine_version__)=='undefined'|| window.__webview_engine_version__+1e-6<0.01+1e-6){return _ev(root);}
}catch(err){
console.log(err)
}
return root;
}
}
}
 
	var BASE_DEVICE_WIDTH = 750;
var isIOS=navigator.userAgent.match("iPhone");
var deviceWidth = window.screen.width || 375;
var deviceDPR = window.devicePixelRatio || 2;
function checkDeviceWidth() {
var newDeviceWidth = window.screen.width || 375
var newDeviceDPR = window.devicePixelRatio || 2
var newDeviceHeight = window.screen.height || 375
if (window.screen.orientation && /^landscape/.test(window.screen.orientation.type || '')) newDeviceWidth = newDeviceHeight
if (newDeviceWidth !== deviceWidth || newDeviceDPR !== deviceDPR) {
deviceWidth = newDeviceWidth
deviceDPR = newDeviceDPR
}
}
checkDeviceWidth()
var eps = 1e-4;
function transformRPX(number, newDeviceWidth) {
if ( number === 0 ) return 0;
number = number / BASE_DEVICE_WIDTH * ( newDeviceWidth || deviceWidth );
number = Math.floor(number + eps);
if (number === 0) {
if (deviceDPR === 1 || !isIOS) {
return 1;
} else {
return 0.5;
}
}
return number;
}
var setCssToHead = function(file, _xcInvalid, info) {
var Ca = {};
var css_id;
var info = info || {};
var _C= [];
function makeup(file, opt) {
var _n = typeof(file) === "number";
if ( _n && Ca.hasOwnProperty(file)) return "";
if ( _n ) Ca[file] = 1;
var ex = _n ? _C[file] : file;
var res="";
for (var i = ex.length - 1; i >= 0; i--) {
var content = ex[i];
if (typeof(content) === "object")
{
var op = content[0];
if ( op == 0 )
res = transformRPX(content[1], opt.deviceWidth) + "px" + res;
else if ( op == 1)
res = opt.suffix + res;
else if ( op == 2 ) 
res = makeup(content[1], opt) + res;
}
else
res = content + res
}
return res;
}
var rewritor = function(suffix, opt, style){
opt = opt || {};
suffix = suffix || "";
opt.suffix = suffix;
if ( opt.allowIllegalSelector != undefined && _xcInvalid != undefined )
{
if ( opt.allowIllegalSelector )
console.warn( "For developer:" + _xcInvalid );
else
{
console.error( _xcInvalid + "This css file is ignored." );
return;
}
}
Ca={};
css = makeup(file, opt);
if ( !style ) 
{
var head = document.head || document.getElementsByTagName('head')[0];
window.__rpxRecalculatingFuncs__ = window.__rpxRecalculatingFuncs__ || [];
style = document.createElement('style');
style.type = 'text/css';
style.setAttribute( "css:path", info.path );
head.appendChild(style);
window.__rpxRecalculatingFuncs__.push(function(size){
opt.deviceWidth = size.width;
rewritor(suffix, opt, style);
});
}
if (style.styleSheet) {
style.styleSheet.cssText = css;
} else {
if ( style.childNodes.length == 0 )
style.appendChild(document.createTextNode(css));
else 
style.childNodes[0].nodeValue = css;
}
}
return rewritor;
}
setCssToHead([])();setCssToHead([])(); 
	 
	;var __subPageFrameEndTime__ = Date.now() 	