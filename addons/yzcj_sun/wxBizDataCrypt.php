<?php
 goto yoWIF; MbkiF: include_once "\145\162\x72\x6f\x72\103\x6f\144\145\x2e\x70\x68\x70"; goto pZdZx; yoWIF: include_once "\160\153\143\163\x37\x45\156\143\157\144\145\x72\x2e\160\x68\160"; goto MbkiF; pZdZx: class WXBizDataCrypt { private $appid; private $sessionKey; public function WXBizDataCrypt($appid, $sessionKey) { $this->sessionKey = $sessionKey; $this->appid = $appid; } public function decryptData($encryptedData, $iv, &$data) { goto YCPYc; gdXFx: if (!(strlen($iv) != 24)) { goto R3NEN; } goto sDi4C; sDi4C: return ErrorCode::$IllegalIv; goto uPFJQ; wVvNU: $dataObj = json_decode($result[1]); goto EJ7Ng; Xvt3o: XRusn: goto ccpLL; CVDDc: return ErrorCode::$IllegalBuffer; goto Xvt3o; XtFKz: return ErrorCode::$OK; goto i1ICh; IcMqP: return $result[0]; goto Fa0ar; EJ7Ng: if (!($dataObj == NULL)) { goto XRusn; } goto CVDDc; JZHPt: $data = $result[1]; goto XtFKz; NWUJN: $aesCipher = base64_decode($encryptedData); goto XgNj0; K0I3O: lqV0_: goto ZFJS6; ccpLL: if (!($dataObj->watermark->appid != $this->appid)) { goto rV0FG; } goto P0mxW; Fa0ar: WmxSG: goto wVvNU; M1sT_: rV0FG: goto JZHPt; QyqDT: $aesIV = base64_decode($iv); goto NWUJN; uPFJQ: R3NEN: goto QyqDT; ZFJS6: $aesKey = base64_decode($this->sessionKey); goto gdXFx; P0mxW: return ErrorCode::$IllegalBuffer; goto M1sT_; aHEob: if (!($result[0] != 0)) { goto WmxSG; } goto IcMqP; dcX5O: return ErrorCode::$IllegalAesKey; goto K0I3O; YCPYc: if (!(strlen($this->sessionKey) != 24)) { goto lqV0_; } goto dcX5O; XgNj0: $pc = new Prpcrypt($aesKey); goto GvYcx; GvYcx: $result = $pc->decrypt($aesCipher, $aesIV); goto aHEob; i1ICh: } }