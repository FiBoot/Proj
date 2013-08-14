#ifndef FILEINFO_H
#define FILEINFO_H

#include <QString>
#include <QDateTime>

class FileInfo
{
public:
    FileInfo();
    FileInfo(QString, int, QDateTime);
    ~FileInfo();
    QString     getName();
    int         getSize();
    QDateTime   getLastModified();
    void        setName(QString name);
    void        setSize(int size);
    void        setLastModified(QDateTime date);

private:
    QString     name;
    int         size;
    QDateTime   lastModified;

};

#endif // FILEINFO_H
