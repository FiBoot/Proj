#include		<string>
#include		<iostream>
#include 		"Network.hpp"


void            client(std::string str)
{
    Network     net;

    net.connection(str);
	while (1)
	{
		//std::cin >> str;
		std::getline(std::cin, str);
		net.sendMsg(str);


		/* test frite */

		std::string arg1;
		std::string arg2;
		std::cout << "str='" << str << "'" << std::endl;

		int pos = 0;
		if ((pos = str.find(" ")) != std::string::npos)
		{
			arg1 = str.substr(0, pos);
			arg2 = str.substr(pos + 1);
			std::cout << "arg1='" << arg1 << "'" << std::endl;
			std::cout << "arg2='" << arg2 << "'" << std::endl;
		}

		
		if (strcmp(arg1.c_str(), "getFile") == 0) //le client telecharge un fichier du serveur
		{
			std::cout << "START file reception" << std::endl;

			char buffer_file[512];
			memset(buffer_file, '\0', 512);
			int read_size = 0;
			FILE *file_descriptor = fopen(arg2.c_str(), "wb");
			while ((read_size = recv(net.sock, buffer_file, sizeof(buffer_file), 0)) > 0)
			{
				int write_size = fwrite(buffer_file, sizeof(char), read_size, file_descriptor);
				if (write_size < read_size)
				{
					std::cerr << "File write failed on client" << std::endl;
				}
				memset(buffer_file, '\0', 512);
				if (read_size != 512)
				{
					break;
				}
			}
			fclose(file_descriptor);

			std::cout << "END file reception" << std::endl;
		}
		else if (strcmp(arg1.c_str(), "sendFile") == 0) //le client envoie un fichier sur le serveur
		{
			std::cout << "START file sending" << std::endl;

			char buffer_file[512];
			memset(buffer_file, '\0', 512);
			int read_size = 0;
			FILE *file_descriptor = fopen(arg2.c_str(), "rb");
			std::cout << "ouverte du fichier" << std::endl;
			while ((read_size = fread(buffer_file, sizeof(char), 512, file_descriptor)) > 0)
			{
				std::cout << "read_size=" << read_size << std::endl;
				if (send(net.sock, buffer_file, read_size, 0) == SOCKET_ERROR)
				{
					std::cerr << "File send failed on client : " << WSAGetLastError() << std::endl;
					break;
				}
				memset(buffer_file, '\0', 512);
			}
			std::cout << "closeFile" << std::endl;
			fclose(file_descriptor);

			std::cout << "END file sending" << std::endl;
		}
				/* end test frite */


	/*	if (str = net.get())
			std::cout << str << std::endl;*/
	}
}
