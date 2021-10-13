# -*- coding: utf-8 -*-
"""CBC attack test1.ipynb

Automatically generated by Colaboratory.

Original file is located at
    https://colab.research.google.com/drive/1-ckTgGwnkyEOlgKUGzgPCaLxAIdVLA5n
"""

!pip uninstall crypto
!pip uninstall pycrypto
!pip install pycrypto

from Crypto.Cipher import AES
from binascii import b2a_hex,a2b_hex

def encrypt(iv,plaintext):
    if len(plaintext)%16 != 0:
        print("plaintext length is invalid")
        return
    if len(iv) != 16:
        print("IV length is invalid")
        return
    key="1234567890123456"
    aes_encrypt = AES.new(key,AES.MODE_CBC,IV=iv)
    return b2a_hex(aes_encrypt.encrypt(plaintext))

def decrypt(iv,cipher):
    if len(iv) != 16:
        print("IV length is invalid")
        return
    key="1234567890123456"
    aes_decrypt = AES.new(key,AES.MODE_CBC,IV=iv)
    return b2a_hex(aes_decrypt.decrypt(a2b_hex(cipher)))

def test():
    iv=b'\x83Z\x8dn\xc3s\xcb\xfe\xe11cP\x00\xb1\xfcg'
    plaintext="0123456789ABCDEFhellocbcflipping"
    print("plaintext: ",plaintext)
    cipher=encrypt(iv, plaintext)
    print("cipher: ",cipher)
    de_cipher = decrypt(iv, cipher)
    print("de_cipher: ",de_cipher)
    print("a2b_hex(de_cipher): ",a2b_hex(de_cipher))
    print()
    #修改1
    bin_cipher = bytearray(a2b_hex(cipher))
    bin_cipher[15] = bin_cipher[15] ^ ord('g') ^ ord('G')
    de_cipher = decrypt(iv,b2a_hex(bin_cipher))
    print('----把最後的g改成G----')
    print("de_cipher2: ",de_cipher)
    print("a2b_hex(de_cipher): ",a2b_hex(de_cipher))
    print()
    #修改2
    bin_decipher = bytearray(a2b_hex(de_cipher))
    bin_iv = bytearray(iv)
    for i in range(len(iv)):
        bin_iv[i] =  bin_iv[i] ^ bin_decipher[i] ^ ord('X')
    de_cipher = decrypt(bytes(bin_iv),b2a_hex(bin_cipher))
    print('----把第一組的字節改成X----')
    print("de_cipher3:",de_cipher)
    print("a2b_hex(de_cipher3): ",a2b_hex(de_cipher))
test()